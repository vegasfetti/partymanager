<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Portfolio;
use App\Models\Review;
use App\Models\Service;
use App\Models\SmartOrder;
use App\Models\Specialist;
use App\Models\SpecImage;
use App\Models\Visit;
use DB;
use Faker\Provider\Image;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;
use Str;

class ProfileController extends Controller
{


    /*

        PROFILE PAGE

    */
    public function index()
    {



        $rating = Review::where('user_id', Auth::id())->avg('rating');
        // сортирока по статусу
        $specialists = Specialist::where('user_id', Auth::id())->orderBy('status', 'desc')->get();
        $visits = Visit::whereHas('specialist', function ($query) {
            $query->where('user_id', auth()->id());
        })->count();        // dd(Auth::user());

        $my_orders = Order::where('user_id', Auth::id())->orderByDesc('created_at')->get();

        $order_for_my_specialists = Order::whereHas('specialist', function ($query) {
            $query->where('user_id', Auth::id());
        })->orderByDesc('created_at')->get();

        $smart_orders = SmartOrder::where('user_id', Auth::id())->orderByDesc('created_at')->get();

        return view('pages.profile.index', [
            'rating' => $rating,
            'specialists' => $specialists,
            'visits' => $visits,
            'orders' => $order_for_my_specialists,
            'my_orders' => $my_orders,
            'smart_orders' => $smart_orders
        ]);
    }



    /*

        PROFILE UPDATE PAGE

    */

    public function edit()
    {
        return view('pages.profile.edit');
    }


    /*

        PROFILE UPDATE ACTIONS

    */

    // Обновление фото профиля и ФИО
    public function updateProfile(Request $request)
    {

        $user = auth()->user();
        $defaultImage = 'profile_upload/default-img.png';

        $request->validate([
            'name' => 'required|string|max:35',
            'preview_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $user->updated_at = now();
        $user->name = $request->input('name');
        $user->save();

        // Если пришёл файл
        if ($request->hasFile('preview_image')) {
            $file = $request->file('preview_image');

            // Временный менеджер для проверки размеров
            $imgInfo = getimagesize($file);
            if ($imgInfo[0] < 200 || $imgInfo[1] < 200) {
                return back()->withErrors(['preview_image' => 'Минимальный размер изображения 200x200 пикселей']);
            }

            // Путь текущего фото
            $currentPhoto = $user->image ?? $defaultImage;

            // Если файл уже стоит у пользователя — ничего не меняем
            if (
                Storage::disk('public')->exists($currentPhoto) &&
                file_get_contents($file->getPathname()) === Storage::disk('public')->get($currentPhoto)
            ) {
                // Ничего не делаем
            } else {
                // Удаляем старое фото, если оно не дефолтное
                if ($currentPhoto !== $defaultImage && Storage::disk('public')->exists($currentPhoto)) {
                    Storage::disk('public')->delete($currentPhoto);
                }

                // Сохраняем новое
                $manager = new ImageManager(new Driver());
                $filename = Str::uuid() . '.webp';
                $path = 'profile_upload/' . $filename;

                $img = $manager->read($file->getPathname())
                    ->scaleDown(width: 1200)
                    ->toWebp(75);

                Storage::disk('public')->put($path, $img->toString());
                $user->image = $path;
            }
        } else {
            // Файл не пришёл — ставим дефолт, удаляя старое (если не дефолт)
            if ($user->image !== $defaultImage && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }
            $user->image = $defaultImage;
        }

        $user->save();

        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Профиль обновлен!'],
        ]);

    }




    // Смена пароля
    public function changePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'old_password' => 'required|string',
            'password' => [
                'required',
                'confirmed',         // Для подтверждения пароля: поле password_confirmation должно быть
                'min:8',
                'regex:/^[A-Za-z\d!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]+$/', // Только латиница + цифры + спецсимволы
                'regex:/[!@#$%^&*()_+\-=\[\]{};:"\\|,.<>\/?]/',          // Хотя бы один спецсимвол
                'different:old_password',
            ],
        ], [
            'old_password.required' => 'Введите текущий пароль',
            'password.different' => 'Новый пароль должен отличаться от текущего',
            'password.confirmed' => 'Подтверждение пароля не совпадает',
            'password.regex' => 'Пароль должен содержать только латинские буквы И хотя бы один спецсимвол',
        ]);

        // Проверка, что введённый старый пароль совпадает с текущим в базе
        if (!Hash::check($request->old_password, $user->password)) {
            return back()->withErrors(['old_password' => 'Неверный текущий пароль']);
        }

        // Сохраняем новый пароль
        $user->password = Hash::make($request->password);
        $user->save();


        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Пароль обновлен!'],
        ]);
    }





    // Удаление аккаунта
    public function deleteAccount(Request $request)
    {

        $user = auth()->user();

        // Валидация: вводим точную фразу
        $request->validate([
            'confirm_text' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value !== 'удалить мой аккаунт навсегда') {
                        $fail('Вы должны ввести точную фразу для удаления аккаунта.');
                    }
                }
            ],
        ]);

        DB::transaction(function () use ($user) {

            // 1. Удаляем аватар пользователя, если он не дефолтный
            if ($user->image && $user->image !== 'profile_upload/default-img.png') {
                if (Storage::disk('public')->exists($user->image)) {
                    Storage::disk('public')->delete($user->image);
                }
            }

            // 2. Удаляем все фотографии карточек и портфолио, а также услуги

            // Получаем все specialists пользователя
            $specialists = Specialist::where('user_id', $user->id)->get();

            foreach ($specialists as $spec) {
                // spec_images
                $specImages = SpecImage::where('specialist_id', $spec->id)->get();
                foreach ($specImages as $img) {
                    if (!str_ends_with($img->image, 'no-photo.png') && Storage::disk('public')->exists($img->image)) {
                        Storage::disk('public')->delete($img->image);
                    }
                }

                // portfolios
                $portfolioImages = Portfolio::where('specialist_id', $spec->id)->get();
                foreach ($portfolioImages as $portfolio) {
                    if (!str_ends_with($portfolio->image, 'no-photo.png') && Storage::disk('public')->exists($portfolio->image)) {
                        Storage::disk('public')->delete($portfolio->image);
                    }
                }

                // services
                $services = Service::where('specialist_id', $spec->id)->get();
                foreach ($services as $service) {
                    if (!str_ends_with($service->image, 'no-photo.png') && Storage::disk('public')->exists($service->image)) {
                        Storage::disk('public')->delete($service->image);
                    }
                }
            }

            // 3. Удаляем specialists, spec_images, portfolios и services каскадом через foreign key
            // (если cascade на FK настроен, то достаточно удалить specialists)

            Specialist::where('user_id', $user->id)->delete();

            // 4. Удаляем самого пользователя
            $user->delete();
        });

        auth()->logout();

        return redirect('/')->with('alerts', [
            ['type' => 'success', 'message' => 'Ваш аккаунт успешно удалён.'],
        ]);
    }



}


