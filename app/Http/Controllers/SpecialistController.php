<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\Specialist;
use App\Models\Subcategory;
use App\Models\Visit;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;



/*
--------------------------------------------------------------------
|
|
|            SPECIALIST
|
|
--------------------------------------------------------------------
*/


class SpecialistController extends Controller
{
    /*
    --------------------------------------------------------------------
    |           INDEX SPECIALISTS
    --------------------------------------------------------------------
    */
    public function index(Request $request, $categorySlug = null, $subcategorySlug = null)
    {

        $current_city = app('current_city');


        $query = Specialist::where('city_id', $current_city->id)
            ->where('status', 'verify')
            ->withCount(['reviews', 'visits'])
            ->withAvg('reviews', 'rating');

        // get cats and subcats
        $categories = Category::with('subcategories')->get();

        $activeCategory = null;
        $activeSubcategory = null;

        if ($categorySlug) {
            $activeCategory = Category::where('slug', $categorySlug)->firstOrFail();
            $query->where('category_id', $activeCategory->id);


            if ($subcategorySlug) {
                $activeSubcategory = $activeCategory->subcategories()->where('slug', $subcategorySlug)->firstOrFail();
                $query->where('subcategory_id', $activeSubcategory->id);
            }
        }


        // ——— Поиск по ключевому слову ———
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }



        // get max and min price
        // $max_price = $query->get()->max('price');
        // $min_price = $query->get()->min('price');


        // вычисляем глобальные min/max по текущему фильтру
        $globalMin = (float) $query->min('price') ?: 0;
        $globalMax = (float) $query->max('price') ?: 0;



        // берём из GET-параметров (или крайние)
        $minPrice = (float) $request->query('min_price', $globalMin);
        $maxPrice = (float) $request->query('max_price', $globalMax);


        // и фильтруем по диапазону
        $query->whereBetween('price', [$minPrice, $maxPrice]);



        // ——— Фильтр по опыту ———
        if ($exp = $request->query('experience')) {
            match ($exp) {
                'less_than_1' => $query->where('experience', 'less_than_1'),
                '1_3_years' => $query->where('experience', '1_3_years'),
                '3_5_years' => $query->where('experience', '3_5_years'),
                'more_than_5' => $query->where('experience', 'more_than_5'),
                default => null,
            };
        }


        // ——— Фильтр по виду занятости ———
        if ($subject_type = $request->query('subject_type')) {
            match ($subject_type) {
                'individual' => $query->where('subject_type', 'individual'),
                'company' => $query->where('subject_type', 'company'),
                default => null,
            };
        }





        // ——— Сортировка ———
        if ($sort = $request->query('sort')) {
            match ($sort) {
                'price' => $query->orderBy('price', 'asc'),
                'rating' => $query->orderBy('reviews_avg_rating', 'desc'),
                'reviews' => $query->orderBy('reviews_count', 'desc'),
                'popularity' => $query->orderBy('visits_count', 'desc'),
                default => null,
            };
        }




        // ——— Чекбоксы ———
        if ($request->has('contract')) {
            $query->where('is_contract', true);
        }
        if ($request->has('verified')) {
            $query->whereNotNull('documents_verified_at');
        }
        if ($request->has('filled')) {
            $query->whereNotNull('vkontacte')
                ->whereNotNull('telegram')
                // ->whereNotNull('video_link')
                ->whereNotNull('website')
                ->whereNotNull('phone')
                ->whereNotNull('email');
        }




        // пагинация с сохр. GET-параметров
        $specialists = $query->orderByRaw("
            CASE 
                WHEN promoted_until >= NOW() THEN 0
                ELSE 1
            END
        ")->orderByDesc('created_at')->paginate(10)->withQueryString();




        // get banners
        $banners = Banner::where('status', true)
            ->where('type', 'specialists')
            ->where(function ($query) use ($current_city) {
                $query->whereNull('city_id')
                    ->orWhere('city_id', $current_city->id);
            })
            ->get();





        return view('pages.specialists.index', compact(
            'specialists',
            'categories',
            'activeCategory',
            'activeSubcategory',
            'globalMin',
            'globalMax',
            'minPrice',
            'maxPrice',
            'search',
            'sort',
            'exp',
            'subject_type',
            'banners'
        ));
    }




    /*
    --------------------------------------------------------------------
    |           SHOW SPECIALIST
    --------------------------------------------------------------------
    */


    public function show(Request $request, $id)
    {
        $specialist = Specialist::where('id', $id)->with([
            'reviews' => function ($query) {
                $query->where('status', 'verify')->latest();
            }
        ])->firstOrFail();



        // visit
        $ip = $request->ip();

        if (auth()->check()) {
            $userId = auth()->id();

            // Для авторизованного пользователя
            $visit = Visit::where('user_id', $userId)
                ->where('specialist_id', $specialist->id)
                ->first();

            if ($visit) {
                // Обновляем существующую запись
                $visit->update([
                    'updated_at' => now(),
                    'ip' => $ip
                ]);
            } else {
                // Создаем новую запись
                Visit::create([
                    'user_id' => $userId,
                    'specialist_id' => $specialist->id,
                    'ip' => $ip
                ]);
            }

            // Перепривязываем все анонимные посещения с этого IP к пользователю
            Visit::whereNull('user_id')
                ->where('ip', $ip)
                ->update([
                    'user_id' => $userId,
                    'updated_at' => now()
                ]);

        } else {
            // Для неавторизованного пользователя

            // 1. Сначала ищем посещения от ЛЮБОГО авторизованного пользователя с этого IP
            $authorizedVisit = Visit::whereNotNull('user_id')
                ->where('ip', $ip)
                ->where('specialist_id', $specialist->id)
                ->first();

            if ($authorizedVisit) {
                // Обновляем запись авторизованного пользователя
                $authorizedVisit->update([
                    'updated_at' => now(),
                    'ip' => $ip
                ]);
            } else {
                // 2. Если нет записей от авторизованных, ищем анонимную запись
                $anonymousVisit = Visit::whereNull('user_id')
                    ->where('ip', $ip)
                    ->where('specialist_id', $specialist->id)
                    ->first();

                if ($anonymousVisit) {
                    // Обновляем анонимную запись
                    $anonymousVisit->update([
                        'updated_at' => now(),
                        'ip' => $ip
                    ]);
                } else {
                    // Создаем новую анонимную запись
                    Visit::create([
                        'specialist_id' => $specialist->id,
                        'ip' => $ip
                    ]);
                }
            }
        }

        if ($specialist->status == 'canceled') {
            return redirect()->route('specialists')->with('alerts', [
                [
                    'type' => 'info',
                    'message' => 'Доступ к этой карточке закрыт',
                ],
            ]);
        }


        if (Auth::check()) {
            if ($specialist->status == 'on_moderation' and Auth::user()->id != $specialist->user_id) {
                return redirect()->route('specialists')->with('alerts', [
                    [
                        'type' => 'info',
                        'message' => 'Карточка специалиста на модерации',
                    ],
                ]);

            }
        } else {
            if ($specialist->status == 'on_moderation') {
                return redirect()->route('specialists')->with('alerts', [
                    [
                        'type' => 'info',
                        'message' => 'Карточка специалиста на модерации',
                    ],
                ]);
            }
        }


        return view('pages.specialists.show', compact('specialist'));



    }

































    /*
    --------------------------------------------------------------------
    |
    |
    |           CREATE SPECIALIST
    |
    |
    --------------------------------------------------------------------
    */

    /*
    --------------------------------------------------------------------
    |           CREATE PAGE
    --------------------------------------------------------------------
    */


    public function createPage()
    {

        $categories = Category::all();
        $subcategories = Subcategory::all();

        // Проверяем лимит — максимум 10 специалистов на пользователя
        $specialistsCount = Specialist::where('user_id', auth()->id())->count();
        if ($specialistsCount >= 10) {
            return redirect()->back()->with('alerts', [
                [
                    'type' => 'info',
                    'message' => 'Вы достигли лимита специалистов (10).',
                ]
            ]);
        }

        return view('pages.specialists.create', compact('categories', 'subcategories'));


    }


    /*
    --------------------------------------------------------------------
    |           CREATE ACTION
    --------------------------------------------------------------------
    */


    // action
    public function createAction(Request $request)
    {

        // Проверяем лимит — максимум 10 специалистов на пользователя
        $specialistsCount = Specialist::where('user_id', auth()->id())->count();
        if ($specialistsCount >= 10) {
            return redirect()->back()->with('alerts', [
                [
                    'type' => 'info',
                    'message' => 'Вы достигли лимита специалистов (10).',
                ]
            ]);
        }

        // dd(Image::class);
        $request->merge([
            'is_contract' => filter_var($request->input('is_contract'), FILTER_VALIDATE_BOOLEAN),
        ]);
        $request->validate([
            'video' => 'nullable|url|max:255',
            'title' => 'required|string|max:255|min:3',
            'description' => 'required|string|max:1000|min:10',
            'price' => 'required|integer|min:1|numeric|max:9999999999',
            'price_type' => 'required|string|in:per_hour,per_day,per_service',
            'phone' => 'required|string|regex:/^\+\d{10,15}$/', // +99999999999
            'email' => 'required|email|max:255',
            'vkontacte' => 'nullable|url|regex:/^(https?:\/\/)?(m\.)?vk\.com\//i|max:255',
            'telegram' => 'nullable|url|regex:/^https:\/\/t\.me\//|max:255',
            'website' => 'nullable|url|regex:/^https?:\/\//|max:255',
            'price_text' => 'required|string|max:1000|min:10',
            'skills' => 'nullable|string|max:1000',
            'equipment' => 'nullable|string|max:1000',
            'languages' => 'nullable|string|max:1000',
            'experience' => 'required|string|in:less_than_1,1_3_years,3_5_years,more_than_5',
            'subject_type' => 'required|string|in:individual,company',
            'is_contract' => 'required|boolean',

            'category_id' => 'required|exists:categories,id',
            'subcategory_id' => [
                'required',
                Rule::exists('subcategories', 'id')->where(function ($query) use ($request) {
                    return $query->where('category_id', $request->category_id);
                }),
            ],

            'preview_images' => 'required|array|min:1|max:10',
            'preview_images.*' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
            'portfolio_images' => 'nullable|array|min:1|max:30',
            'portfolio_images.*' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
        ]);



        $specialist = Specialist::create([
            'title' => $request->title,
            'description' => strip_tags(string: $request->description),
            'video_link' => $request->video,
            'price' => $request->price,
            'price_type' => $request->price_type,
            'phone' => $request->phone,
            'email' => $request->email,
            'vkontacte' => $request->vkontacte,
            'telegram' => $request->telegram,
            'website' => $request->website,
            'price_text' => strip_tags($request->price_text),
            'skills' => $request->skills,
            'equipment' => $request->equipment,
            'languages' => $request->input('languages'),
            'experience' => $request->experience,
            'subject_type' => $request->subject_type,
            'is_contract' => $request->is_contract,
            'category_id' => $request->category_id,
            'subcategory_id' => $request->subcategory_id,
            'city_id' => 2, // если нужно
            'user_id' => auth()->id(),
        ]);



        // Создаем менеджер изображений один раз
        $manager = new ImageManager(new Driver());

        // Preview images
        $previewFiles = $request->file('preview_images');
        if (!$previewFiles || count($previewFiles) === 0) {
            return redirect()->back()->with('error', 'Пожалуйста, добавьте хотя бы одно изображение.');
        }

        foreach ($previewFiles as $image) {
            $filename = Str::uuid() . '.webp';
            $path = 'spec_upload/' . $filename;

            $img = $manager->read($image->getPathname())
                ->scaleDown(width: 1200)
                ->toWebp(80);

            Storage::disk('public')->put($path, $img->toString());

            $specialist->specImages()->create([
                'image' => $path,
            ]);
        }

        // Portfolio images
        $portfolioFiles = $request->file('portfolio_images');
        if ($portfolioFiles && count($portfolioFiles) > 0) {
            foreach ($portfolioFiles as $image) {
                $filename = Str::uuid() . '.webp';
                $path = 'portfolio_upload/' . $filename;

                $img = $manager->read($image->getPathname())
                    ->scaleDown(width: 1200)
                    ->toWebp(75);

                Storage::disk('public')->put($path, $img->toString());

                $specialist->portfolios()->create([
                    'image' => $path,
                ]);
            }
        }


        return redirect()->route('specialists.show', $specialist->id)->with('alerts', [
            [
                'type' => 'success',
                'message' => 'Каротчка создана и отправлена на модерацию'
            ]
        ]);

    }


























    /*
    --------------------------------------------------------------------
    |
    |
    |           UPDATE SPECIALIST
    |
    |
    --------------------------------------------------------------------
    */

    /*
      --------------------------------------------------------------------
      |           UPDATE PAGE
      --------------------------------------------------------------------
      */

    public function edit(Specialist $specialist)
    {
        $this->authorizeSpecialist($specialist);

        $categories = Category::all();
        $subcategories = Subcategory::all();


        $existingPreviewImages = $specialist->specImages->map(function ($img) {
            $path = storage_path('app/public/' . $img->image);

            return [
                'source' => asset('storage/' . $img->image), // Основной URL файла
                'options' => [
                    'type' => 'local',
                    'file' => [
                        'name' => basename($img->image),
                        'size' => file_exists($path) ? filesize($path) : 0,
                        'type' => mime_content_type($path) ?: 'image/jpeg',
                    ],
                    'metadata' => [
                        'id' => $img->id,
                        'poster' => asset('storage/' . $img->image), // Ключевое изменение!
                    ],
                ],
            ];
        })->toArray();



        $existingPortfolioImages = $specialist->portfolios->map(function ($img) {
            $path = storage_path('app/public/' . $img->image);
            return [
                'source' => asset('storage/' . $img->image),
                'options' => [
                    'type' => 'local',
                    'file' => [
                        'name' => basename($img->image),
                        'size' => file_exists($path) ? filesize($path) : 0,
                        'type' => mime_content_type($path) ?: 'image/jpeg',
                    ],
                    'metadata' => [
                        'id' => $img->id,
                        'poster' => asset('storage/' . $img->image),
                    ],
                ],
            ];
        })->toArray();

        return view('pages.specialists.edit', [
            'specialist' => $specialist,
            'existingPreviewImages' => $existingPreviewImages,
            'existingPortfolioImages' => $existingPortfolioImages,
            'categories' => $categories,
            'subcategories' => $subcategories,
        ]);
    }




    /*
    --------------------------------------------------------------------
    |           UPDATE ACTION
    --------------------------------------------------------------------
    */

    public function update(Request $request, Specialist $specialist)
    {
        try {

            // dd($request->all());

            $this->authorizeSpecialist($specialist);

            // $request->merge([
            //     'is_contract' => filter_var($request->input('is_contract'), FILTER_VALIDATE_BOOLEAN),
            // ]);

            // $request->validate([
            //     'video' => 'nullable|url|max:255',
            //     'title' => 'required|string|max:255|min:3',
            //     'description' => 'required|string|max:1000|min:10',
            //     'price' => 'required|integer|min:1|numeric|max:9999999999',
            //     'price_type' => 'required|string|in:per_hour,per_day,per_service',
            //     'phone' => 'required|string|regex:/^\+\d{10,15}$/', // +99999999999
            //     'email' => 'required|email|max:255',
            //     'vkontacte' => 'nullable|url|regex:/^(https?:\/\/)?(m\.)?vk\.com\//i|max:255',
            //     'telegram' => 'nullable|url|regex:/^https:\/\/t\.me\//|max:255',
            //     'website' => 'nullable|url|regex:/^https?:\/\//|max:255',
            //     'price_text' => 'required|string|max:1000|min:10',
            //     'skills' => 'nullable|string|max:1000',
            //     'equipment' => 'nullable|string|max:1000',
            //     'languages' => 'nullable|string|max:1000',
            //     'experience' => 'required|string|in:less_than_1,1_3_years,3_5_years,more_than_5',
            //     'subject_type' => 'required|string|in:individual,company',
            //     'is_contract' => 'required|boolean',

            //     'category_id' => 'required|exists:categories,id',
            //     'subcategory_id' => 'required|exists:subcategories,id',

            //     'existing_preview_images' => 'sometimes|array',
            //     'existing_preview_images.*' => 'numeric|exists:spec_images,id',
            //     'new_preview_images' => 'sometimes|array|max:10',
            //     'new_preview_images.*' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp',

            //     'existing_portfolio_images' => 'sometimes|array',
            //     'existing_portfolio_images.*' => 'numeric|exists:portfolio_images,id',
            //     'new_portfolio_images' => 'nullable|array|max:30',
            //     'new_portfolio_images.*' => 'file|mimetypes:image/jpeg,image/png,image/gif,image/webp',
            // ]);

            // // Обновляем основные данные
            // $specialist->update([
            //     'title' => $request->title,
            //     'description' => strip_tags(string: $request->description),
            //     'video_link' => $request->video,
            //     'price' => $request->price,
            //     'price_type' => $request->price_type,
            //     'phone' => $request->phone,
            //     'email' => $request->email,
            //     'vkontacte' => $request->vkontacte,
            //     'telegram' => $request->telegram,
            //     'website' => $request->website,
            //     'price_text' => strip_tags($request->price_text),
            //     'skills' => $request->skills,
            //     'equipment' => $request->equipment,
            //     'languages' => $request->input('languages'),
            //     'experience' => $request->experience,
            //     'subject_type' => $request->subject_type,
            //     'is_contract' => $request->is_contract,
            //     'category_id' => $request->category_id,
            //     'subcategory_id' => $request->subcategory_id,
            // ]);

            // $manager = new ImageManager(new Driver());

            // // Обработка превью-изображений
            // $this->processImagesUpdate(
            //     $specialist,
            //     $request,
            //     'preview_images',
            //     $specialist->specImages(),
            //     'spec_upload',
            //     $manager
            // );

            // // Обработка портфолио-изображений
            // $this->processImagesUpdate(
            //     $specialist,
            //     $request,
            //     'portfolio_images',
            //     $specialist->portfolios(),
            //     'portfolio_upload',
            //     $manager
            // );

            // return redirect()->route('specialists.show', ['id' => $specialist->id])
            //     ->with('alert', [['type' => 'success', 'message' => 'Карточка успешно обновлена']]);


            $request->merge([
                'is_contract' => filter_var($request->input('is_contract'), FILTER_VALIDATE_BOOLEAN),
            ]);

            // Валидируем
            $request->validate([
                'preview_images' => 'required|array|min:1|max:10',
                'preview_images.*' => 'nullable',
                'preview_meta' => 'required|string',

                'portfolio_images' => 'nullable|array|min:1|max:30',
                'portfolio_images.*' => 'nullable',
                'portfolio_meta' => 'nullable|string',
                // Остальные правила, как в create
                'video' => 'nullable|url|max:255',
                'title' => 'required|string|max:255|min:3',
                'description' => 'required|string|max:1000|min:10',
                'price' => 'required|integer|min:1|numeric|max:9999999999',
                'price_type' => 'required|string|in:per_hour,per_day,per_service',
                'phone' => 'required|string|regex:/^\+\d{10,15}$/', // +99999999999
                'email' => 'required|email|max:255',
                'vkontacte' => 'nullable|url|regex:/^(https?:\/\/)?(m\.)?vk\.com\//i|max:255',
                'telegram' => 'nullable|url|regex:/^https:\/\/t\.me\//|max:255',
                'website' => 'nullable|url|regex:/^https?:\/\//|max:255',
                'price_text' => 'required|string|max:1000|min:10',
                'skills' => 'nullable|string|max:1000',
                'equipment' => 'nullable|string|max:1000',
                'languages' => 'nullable|string|max:1000',
                'experience' => 'required|string|in:less_than_1,1_3_years,3_5_years,more_than_5',
                'subject_type' => 'required|string|in:individual,company',
                'is_contract' => 'required|boolean',

                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => [
                    'required',
                    Rule::exists('subcategories', 'id')->where(function ($query) use ($request) {
                        return $query->where('category_id', $request->category_id);
                    }),
                ],
            ]);

            $manager = new ImageManager(new Driver());

            // ====== PREVIEW IMAGES ======
            $meta = json_decode($request->preview_meta, true);
            $newFiles = $request->file('preview_images', []);

            // Карта новых файлов по имени
            $filesByName = [];
            foreach ($newFiles as $f) {
                $filesByName[$f->getClientOriginalName()][] = $f;
            }

            $newPreviewOrder = [];

            foreach ($meta as $item) {
                if ($item['type'] === 'existing') {
                    $relativePath = str_replace(asset('storage') . '/', '', $item['source']);
                    $newPreviewOrder[] = $relativePath;
                } else {
                    $name = $item['name'];
                    if (!empty($filesByName[$name]) && is_array($filesByName[$name])) {
                        $file = array_shift($filesByName[$name]);
                    } else {
                        $file = null;
                    }
                    if ($file) {
                        $filename = Str::uuid() . '.webp';
                        $path = 'spec_upload/' . $filename;

                        $img = $manager->read($file->getPathname())
                            ->scaleDown(width: 1200)
                            ->toWebp(80);

                        Storage::disk('public')->put($path, $img->toString());
                        $newPreviewOrder[] = $path;
                    }
                }
            }

            // Удаление лишних preview
            $existingPreviewPaths = $specialist->specImages->pluck('image')->toArray();
            $toDelete = array_diff($existingPreviewPaths, $newPreviewOrder);

            foreach ($toDelete as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $specialist->specImages()->delete();
            foreach ($newPreviewOrder as $path) {
                $specialist->specImages()->create(['image' => $path]);
            }

            // ====== PORTFOLIO IMAGES ======
            $portfolioMeta = json_decode($request->portfolio_meta ?? '[]', true);
            $portfolioNewFiles = $request->file('portfolio_images', []);

            $portfolioFilesByName = [];
            foreach ($portfolioNewFiles as $f) {
                $portfolioFilesByName[$f->getClientOriginalName()][] = $f;
            }

            $newPortfolioOrder = [];

            foreach ($portfolioMeta as $item) {
                if ($item['type'] === 'existing') {
                    $relativePath = str_replace(asset('storage') . '/', '', $item['source']);
                    $newPortfolioOrder[] = $relativePath;
                } else {
                    $name = $item['name'];





                    if (!empty($portfolioFilesByName[$name]) && is_array($portfolioFilesByName[$name])) {
                        $file = array_shift($portfolioFilesByName[$name]);
                    } else {
                        $file = null;
                    }



                    if ($file) {
                        $filename = Str::uuid() . '.webp';
                        $path = 'portfolio_upload/' . $filename;

                        $img = $manager->read($file->getPathname())
                            ->scaleDown(width: 1200)
                            ->toWebp(75);

                        Storage::disk('public')->put($path, $img->toString());
                        $newPortfolioOrder[] = $path;
                    }
                }
            }

            $existingPortfolioPaths = $specialist->portfolios->pluck('image')->toArray();
            $toDeletePortfolio = array_diff($existingPortfolioPaths, $newPortfolioOrder);

            foreach ($toDeletePortfolio as $oldPath) {
                Storage::disk('public')->delete($oldPath);
            }

            $specialist->portfolios()->delete();
            foreach ($newPortfolioOrder as $path) {
                $specialist->portfolios()->create(['image' => $path]);
            }

            // ====== UPDATE OTHER FIELDS ======
            $specialist->update([
                'title' => $request->title,
                'description' => strip_tags($request->description),
                'video_link' => $request->video,
                'price' => $request->price,
                'price_type' => $request->price_type,
                'phone' => $request->phone,
                'email' => $request->email,
                'vkontacte' => $request->vkontacte,
                'telegram' => $request->telegram,
                'website' => $request->website,
                'price_text' => strip_tags($request->price_text),
                'skills' => $request->skills,
                'equipment' => $request->equipment,
                'languages' => $request->input('languages'),
                'experience' => $request->experience,
                'subject_type' => $request->subject_type,
                'is_contract' => $request->is_contract,
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'status' => 'on_moderation',
                'updated_at' => now(),
            ]);

            return redirect()->route('specialists.show', $specialist->id)
                ->with('alerts', [['type' => 'success', 'message' => 'Карточка обновлена и отправлена на модерацию']]);


        } catch (\Throwable $e) {
            return redirect()->back()
                ->with('alerts', [['type' => 'error', 'message' => $e->getMessage()]]);
            ;
        }
    }

    /*
    --------------------------------------------------------------------
    |           UPDATE MIDDLEWARE
    --------------------------------------------------------------------
    */

    private function processImagesUpdate($specialist, $request, $type)
    {
        $relation = $type === 'preview'
            ? $specialist->specImages()
            : $specialist->portfolios();

        // Получаем текущие изображения
        $currentImages = $relation->pluck('id')->toArray();

        // Сохранённые изображения из формы
        $existingIds = $request->input("existing_{$type}_images", []);

        // Удаляем отсутствующие изображения
        $toDelete = array_diff($currentImages, $existingIds);
        if (!empty($toDelete)) {
            $relation->whereIn('id', $toDelete)->delete();
        }

        // Добавляем новые изображения
        if ($request->hasFile("new_{$type}_images")) {
            foreach ($request->file("new_{$type}_images") as $file) {
                // Ваш код обработки файла...
                $path = $file->store("{$type}_uploads", 'public');
                $relation->create(['image' => $path]);
            }
        }
    }

    private function updateImageOrder($relation, $request, $inputName, $imageIds)
    {
        $submittedOrder = $request->input($inputName, []);

        foreach ($submittedOrder as $index => $url) {
            $path = parse_url($url, PHP_URL_PATH);
            $filename = basename($path);
            $id = pathinfo($filename, PATHINFO_FILENAME);

            if (is_numeric($id) && in_array($id, $imageIds)) {
                $relation->where('id', $id)->update(['order' => $index]);
            }
        }
    }




















    /*
    --------------------------------------------------------------------
    |
    |
    |           DELETE SPECIALIST
    |
    |
    --------------------------------------------------------------------
    */


    public function destroy(Specialist $specialist)
    {
        $this->authorizeSpecialist($specialist);

        // Удаляем preview-изображения
        foreach ($specialist->specImages as $image) {
            if (
                !str_ends_with($image->image, 'no-photo.png') &&
                Storage::disk('public')->exists($image->image)
            ) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }

        // Удаляем portfolio-изображения
        foreach ($specialist->portfolios as $portfolio) {
            if (
                !str_ends_with($portfolio->image, 'no-photo.png') &&
                Storage::disk('public')->exists($portfolio->image)
            ) {
                Storage::disk('public')->delete($portfolio->image);
            }
            $portfolio->delete();
        }

        // Удаляем услуги (services)
        foreach ($specialist->services as $service) {
            if (
                !empty($service->image) &&
                !str_ends_with($service->image, 'no-photo.png') &&
                Storage::disk('public')->exists($service->image)
            ) {
                Storage::disk('public')->delete($service->image);
            }
            $service->delete();
        }

        // Удаляем саму карточку
        $specialist->delete();

        return redirect()->route('profile')->with('alerts', [
            ['type' => 'success', 'message' => 'Карточка и всё связанное удалено']
        ]);
    }



    /*
    --------------------------------------------------------------------
    |           DELETE MIDDLEWARE
    --------------------------------------------------------------------
    */

    protected function authorizeSpecialist(Specialist $specialist)
    {
        if (auth()->id() !== $specialist->user_id) {
            return abort(403)->with('alerts', [
                ['type' => 'error', 'message' => 'У вас нет доступа к этой карточки']
            ]);
        }
    }
}


