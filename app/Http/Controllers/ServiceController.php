<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Specialist;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServiceController extends Controller
{


    // page
    public function createPage($id)
    {

        $spcialist = Specialist::findOrFail($id);

        if($spcialist->user_id !== Auth::user()->id){
            return redirect()->back()->with('alerts', [
                ['type' => 'error', 'message' => 'У вас нет доступа к этому специалисту']
            ]);
        }

        if ($spcialist->services()->count() >= 10) {
            return redirect()->back()->with('alerts', [
                ['type' => 'info', 'message' => 'Максимум 10 услуг у специалиста']
            ]);
        }

        if($spcialist->status != 'verify'){
            return redirect()->back()->with('alerts', [
                ['type' => 'info', 'message' => 'Специалист должен быть проверен модератором']
            ]);
        }

        if($spcialist->documents_verified_at == null){
            return redirect()->back()->with('alerts', [
                ['type' => 'info', 'message' => 'Сначала запросите проверку специалиста']
            ]);
        }

        return view('pages.service.create', ['specialist' => $spcialist]);
    }

    // add
    public function createAction(Request $request, $id)
    {

        // Проверяем авторизацию
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('alerts', [
                ['type' => 'error', 'message' => 'Необходимо авторизоваться']
            ]);
        }

        // Проверяем, что передан specialist_id и что текущий пользователь — владелец специалиста

        $specialist = Specialist::find($id);

        if (!$specialist) {
            return redirect()->back()->with('alerts', [
                ['type' => 'error', 'message' => 'Специалист не найден']
            ]);
        }

        if ($specialist->user_id !== $user->id) {
            return redirect()->back()->with('alerts', [
                ['type' => 'error', 'message' => 'У вас нет доступа к этой карточки']
            ]);
        }



        // Ограничение — максимум 10 услуг у специалиста
        $servicesCount = Service::where('specialist_id', $id)->count();
        if ($servicesCount >= 10) {
            return redirect()->back()->with('alerts', [
                ['type' => 'error', 'message' => 'Максимум 10 услуг у специалиста']
            ]);
        }

        // Валидация
        $request->validate([
            'title' => 'required|string|max:100|min:3',
            'description' => 'nullable|string|max:255',
            'price' => 'required|integer|min:1|numeric|max:9999999999',
            'preview_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // 1 Мб
        ],
            [
                'title.required' => 'Введите название услуги',
                'description.required' => 'Введите описание услуги',
                'price.required' => 'Введите стоимость услуги',
                'preview_image.required' => 'Выберите изображение',
            ]);

        // Проверяем минимальный размер изображения (200x200)
        $image = $request->file('preview_image');
        $imgInfo = getimagesize($image);
        if ($imgInfo[0] < 200 || $imgInfo[1] < 200) {
            return redirect()->back()->withErrors(['preview_image' => 'Минимальный размер изображения 200x200 пикселей']);
        }

        $manager = new ImageManager(new Driver());

        $filename = Str::uuid() . '.webp';
        $path = 'service_upload/' . $filename;

        $img = $manager->read($image->getPathname())
            ->scaleDown(width: 1200)
            ->toWebp(75);

        Storage::disk('public')->put($path, $img->toString());

        // Сохраняем услугу, связав со специалистом
        $service = new Service();
        $service->specialist_id = $id;
        $service->title = $request->input('title');
        $service->description = $request->input('description');
        $service->price = $request->input('price');
        $service->image = $path;
        $service->save();

        return redirect()->back()->with(
            'alerts',
            [['type' => 'success', 'message' => 'Услуга успешно добавлена']]
        );

    }


    // delete
    public function deleteService($id)
    {

        if (!auth()->check()) {
            back()->with('alerts', [
                ['type' => 'error', 'message' => 'Необходимо авторизоваться']
            ]);
        }
        // Находим услугу
        $service = Service::findOrFail($id);

        // Проверяем, что текущий пользователь — автор услуги
        if ($service->specialist->user_id !== auth()->id()) {
            back()->with('alerts', [
                ['type' => 'error', 'message' => 'У вас нет доступа к этой услуге']
            ]);
        }

        // Если фото не дефолтное — удаляем из storage
        if ($service->image && $service->image !== 'service_upload/no-photo.png') {
            Storage::disk('public')->delete($service->image);
        }

        // Удаляем услугу
        $service->delete();

        return redirect()->back()->with('alerts', [
            ['type' => 'success', 'message' => 'Услуга успешно удалена']
        ]);
    }
}
