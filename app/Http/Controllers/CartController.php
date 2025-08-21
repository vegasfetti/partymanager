<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CartController extends Controller
{




    public function index()
    {
        $cartItems = Cart::where('user_id', auth()->id())
            ->with('specialist')
            ->get();

        return view('pages.smart-order.index', compact('cartItems'));
    }






    public function add(Request $request)
    {
        $request->validate([
            'specialist_id' => [
                'required',
                Rule::exists('specialists', 'id')->where(function ($query) {
                    $query->where('status', 'verify');
                }),
            ],
        ]);

        // Проверка: нельзя добавить самого себя
        $specialist = Specialist::find($request->specialist_id);
        if ($specialist->user_id === auth()->id()) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Вы не можете забронировать самого себя'],
            ]);
        }

        // Проверка: уже в корзине
        $exists = Cart::where('user_id', auth()->id())
            ->where('specialist_id', $request->specialist_id)
            ->exists();

        if ($exists) {
            return back()->with('alerts', [
                ['type' => 'info', 'message' => 'Этот исполнитель уже в списке умного бронирования'],
            ]);
        }

        // Проверка: не более 10 в корзине
        $count = Cart::where('user_id', auth()->id())->count();
        if ($count >= 10) {
            return back()->with('alerts', [
                ['type' => 'error', 'message' => 'Нельзя добавить больше 10 исполнителей'],
            ]);
        }

        Cart::firstOrCreate([
            'user_id' => auth()->id(),
            'specialist_id' => $request->specialist_id
        ]);

        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Исполнитель добавлен в умное бронирование'],
        ]);
    }






    public function remove($id)
    {
        Cart::where('user_id', auth()->id())
            ->where('specialist_id', $id)
            ->delete();

        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Исполнитель удален из умного бронирования'],
        ]);
    }






    public function clear()
    {
        Cart::where('user_id', auth()->id())->delete();

        return back()->with('success', 'Корзина очищена');
    }


    public function payed(){
        
    }
}
