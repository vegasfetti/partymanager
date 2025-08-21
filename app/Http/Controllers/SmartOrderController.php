<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\SmartOrder;
use App\Models\SmartOrderSpecialist;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use YooKassa\Client;

class SmartOrderController extends Controller
{

    public function add(Request $request)
    {


        $carts = Cart::where('user_id', auth()->id())->get();



        $request->validate([
            'phone' => [
                'required',
                'string',
                'regex:/^\+\d{10,15}$/', // формат +79998887766
            ],
            'network_link' => [
                'required',
                'string',
                'url',
                'max:255',
            ],
            'current_date' => [
                'required',
                function ($attribute, $value, $fail) {
                    $singlePattern = '/^\d{2}\.\d{2}\.\d{4}$/';
                    $rangePattern = '/^\d{2}\.\d{2}\.\d{4} — \d{2}\.\d{2}\.\d{4}$/u';

                    $today = Carbon::today();

                    // Одна дата
                    if (preg_match($singlePattern, $value)) {
                        try {
                            $date = Carbon::createFromFormat('d.m.Y', $value);

                            if ($date->lt($today)) {
                                $fail('Дата не может быть в прошлом.');
                            }
                        } catch (\Exception $e) {
                            $fail('Неверный формат даты.');
                        }
                        return;
                    }

                    // Диапазон дат
                    if (preg_match($rangePattern, $value)) {
                        [$start, $end] = explode(' — ', $value);

                        try {
                            $startDate = Carbon::createFromFormat('d.m.Y', $start);
                            $endDate = Carbon::createFromFormat('d.m.Y', $end);

                            if ($startDate->lt($today)) {
                                $fail('Дата начала не может быть в прошлом.');
                            }

                            if ($startDate->diffInDays($endDate) > 31) {
                                $fail('Разница между датами не должна превышать 1 месяц.');
                            }
                        } catch (\Exception $e) {
                            $fail('Неверный формат даты.');
                        }
                        return;
                    }

                    $fail('Неверный формат даты.');
                }
            ],
            'comment' => [
                'nullable',
                'string',
                'max:255',
            ],
            'agreement' => [
                'required',
                Rule::in(['on']),
            ],
        ]);



        //dd(config('services.yookassa.shop_id'), config('services.yookassa.secret'));
        // --- ЮKassa: создаём платёж ---
        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret'));

        $payment = $client->createPayment([
            'amount' => [
                'value' => '5000.00',
                'currency' => 'RUB',
            ],
            'confirmation' => [
                'type' => 'redirect',
                'return_url' => env('YOOKASSA_SUCCESS_URL'),
            ],
            'capture' => true,
            'description' => 'Оплата умного бронирования',
        ], uniqid('', true));

        // сохраняем платеж и данные бронирования
        DB::table('payments')->insert([
            'payment_id' => $payment->id,
            'user_id' => auth()->id(),
            'data' => json_encode($request->all()),
            'status' => 'pending',
            'created_at' => now(),
        ]);

        return redirect($payment->confirmation->confirmation_url);


        // создаём бронь

        // $carts = Cart::where('user_id', auth()->id())->get();

        // DB::transaction(function () use ($request, $carts) {
        //     // 1. Создаём умное бронирование
        //     $smartOrder = SmartOrder::create([
        //         'phone' => $request->phone,
        //         'social_network' => $request->network_link,
        //         'current_date' => $request->current_date,
        //         'comment' => $request->comment,
        //         'status' => 'waiting', // или что-то по умолчанию
        //         'user_id' => auth()->id(),
        //     ]);

        //     // 2. Записываем специалистов из корзины
        //     foreach ($carts as $cart) {
        //         SmartOrderSpecialist::create([
        //             'smart_order_id' => $smartOrder->id,
        //             'specialist_id' => $cart->specialist_id,
        //         ]);
        //     }

        //     // 3. Очищаем корзину
        //     Cart::where('user_id', auth()->id())->delete();
        // });

        // return redirect()->route('profile')->withFragment('smartbooking')->with('alrts', [
        //     ['type' => 'success', 'message' => 'Умное бронирование создано'],
        // ]);
    }
}
