<?php

namespace App\Http\Controllers;

use App\Mail\SpecialistBookedNotification;
use App\Models\Order;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function sendOrder(Request $request)
    {
        // dd($request->all);


        $request->validate([
            'phone' => 'required|string|regex:/^\+\d{10,15}$/',
            'comment' => 'nullable|string|max:255',
            'specialist_id' => 'required|exists:specialists,id',
        ]);

        $specialist = Specialist::findOrFail($request->specialist_id);

        if ($specialist->user_id == auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Вы не можете оставить заявку самому себе!'],
            ]);
        }


        // Проверка на частоту заявок
        $lastOrder = Order::where('user_id', Auth::id())
            ->where('specialist_id', $request->specialist_id)
            ->latest()
            ->first();

        if ($lastOrder && $lastOrder->created_at->diffInHours(now()) < 10) {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Вы уже отправляли заявку этому исполнителю. Попробуйте снова через ' . (10 - $lastOrder->created_at->diffInHours(now())) . ' ч.'],
            ]);
        }

        // Удаляем старую заявку, если она есть
        Order::where('user_id', Auth::id())
            ->where('specialist_id', $request->specialist_id)
            ->delete();

        $order = Order::create([
            'phone' => $request->phone,
            'comment' => $request->comment,
            'specialist_id' => $request->specialist_id,
            'user_id' => Auth::id(),
            'status' => 'waiting',
        ]);


        // try {
        //     Mail::to($specialist->user->email)
        //         ->send(new SpecialistBookedNotification($order));

        // } catch (\Exception $e) {
        //     return back()->with('alerts', [
        //         ['type' => 'error', 'message' => 'Произошла ошибка при отправке письма. Попробуйте позже.'],
        //     ]);
        // }


        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Заявка отправлена исполнителю!'],
        ]);
    }


    public function deleteOrder($id)
    {

        $order = Order::findOrFail($id);

        if ($order->user_id != auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Вы не можете удалить эту заявку'],
            ]);
        }

        if ($order->status != 'waiting') {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Можно удалить только ожидающие заявки'],
            ]);
        }
        $order->delete();
        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Заявка удалена'],
        ]);



    }

    public function orderConfirm($id)
    {

        $order = Order::findOrFail($id);
        if ($order->specialist->user_id != auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Вы не можете подтвердить эту заявку'],
            ]);
        }

        $order->status = 'verify';
        $order->save();
        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Заявка подтверждена, свяжитесь с клиентом'],
        ]);
    }


    public function orderCancel($id)
    {
        $order = Order::findOrFail($id);
        if ($order->specialist->user_id != auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Вы не можете отменить эту заявку'],
            ]);
        }

        $order->status = 'canceled';
        $order->save();
        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Заявка отменена'],
        ]);
    }

}

