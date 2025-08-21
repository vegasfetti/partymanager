<?php

namespace App\Http\Controllers;

use App\Models\Specialist;
use Illuminate\Http\Request;

class RequestController extends Controller
{
    //promo
    public function promo($id)
    {
        $specialist = Specialist::findOrFail($id);

        // сециялит не найден
        if (!$specialist) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Карточка специалиста не найдена'],
            ]);
        }

        // не ваш специалист
        if ($specialist->user_id != auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Это не ваш специалист'],
            ]);
        }

        // нет подвреждение
        if ($specialist->status != 'verify') {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Карточка должна пройти проверку модерацией'],
            ]);
        }

        // уже в топе
        if ($specialist->promoted_until > now()) {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Карточка специалиста уже находится в топе'],
            ]);
        }

        $requestt = \App\Models\Request::where('specialist_id', $specialist->id)->where('type', 'promo')->first();
        //уже есть заявка
        if ($requestt) {
            if ($requestt->updated_at->lte(now()->subDays(7))) {

                $requestt->update([
                    'status' => 'waiting',
                    'updated_at' => now(),
                ]);

                return back()->with('alerts', [
                    ['type' => 'success', 'message' => 'Заявка повторно отправлена'],
                ]);
            } else {
                return back()->with('alerts', [
                    ['type' => 'info', 'message' => 'Должно пройти не менее 7 дней с последней заявки'],
                ]);
            }
        } else {
            \App\Models\Request::create([
                'user_id' => auth()->id(),
                'specialist_id' => $specialist->id,
                'type' => 'promo',
                'status' => 'waiting',
            ]);

            return back()->with('alerts', [
                ['type' => 'success', 'message' => 'Заявка на промо отправлена, скоро мы с вами свяжемся'],
            ]);
        }

    }


    public function verification($id)
    {
        $specialist = Specialist::findOrFail($id);

        // сециялит не найден
        if (!$specialist) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Карточка специалиста не найдена'],
            ]);
        }

        // не ваш специалист
        if ($specialist->user_id != auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Это не ваш специалист'],
            ]);
        }

        // нет подвреждение
        if ($specialist->status != 'verify') {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Карточка должна пройти проверку модерацией'],
            ]);
        }

        // уже проверен
        if ($specialist->documents_verified_at != null) {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Карточка специалиста уже проверена'],
            ]);
        }

        $requestt = \App\Models\Request::where('specialist_id', $specialist->id)->where('type', 'verification')->first();
        //уже есть заявка
        if ($requestt) {
            if ($requestt->updated_at->lte(now()->subDays(7))) {

                $requestt->update([
                    'status' => 'waiting',
                    'updated_at' => now(),
                ]);

                return back()->with('alerts', [
                    ['type' => 'success', 'message' => 'Заявка на проверку повторно отправлена'],
                ]);
            } else {
                return back()->with('alerts', [
                    ['type' => 'info', 'message' => 'Должно пройти не менее 7 дней с последней заявки'],
                ]);
            }
        } else {
            \App\Models\Request::create([
                'user_id' => auth()->id(),
                'specialist_id' => $specialist->id,
                'type' => 'verification',
                'status' => 'waiting',
            ]);

            return back()->with('alerts', [
                ['type' => 'success', 'message' => 'Заявка на проверку отправлена, скоро мы с вами свяжемся'],
            ]);
        }
    }
}
