<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\SmartOrder;
use App\Models\SmartOrderSpecialist;
use DB;
use Illuminate\Http\Request;
use YooKassa\Client;

class PaymentController extends Controller
{
    public function webhook(Request $request)
    {
        
        $data = $request->all();
        $paymentId = $data['object']['id'] ?? null;

        if (!$paymentId) {
            return response()->json(['status' => 'no_payment_id'], 400);
        }

        $client = new Client();
        $client->setAuth(config('services.yookassa.shop_id'), config('services.yookassa.secret'));

        $payment = $client->getPaymentInfo($paymentId);

        // находим нашу запись по payment_id
        $record = DB::table('payments')->where('payment_id', $paymentId)->first();

        if (!$record) {
            return response()->json(['status' => 'not_found'], 404);
        }

        if ($payment->status === 'succeeded' && $record->status !== 'paid') {
            $orderData = json_decode($record->data, true);
            $carts = Cart::where('user_id', $record->user_id)->get();

            DB::transaction(function () use ($orderData, $carts, $record) {
                $smartOrder = SmartOrder::create([
                    'phone' => $orderData['phone'],
                    'social_network' => $orderData['network_link'],
                    'current_date' => $orderData['current_date'],
                    'comment' => $orderData['comment'] ?? null,
                    'status' => 'waiting',
                    'user_id' => $record->user_id,
                ]);

                foreach ($carts as $cart) {
                    SmartOrderSpecialist::create([
                        'smart_order_id' => $smartOrder->id,
                        'specialist_id' => $cart->specialist_id,
                    ]);
                }

                Cart::where('user_id', $record->user_id)->delete();

                DB::table('payments')->where('id', $record->id)->update([
                    'status' => 'paid',
                    'updated_at' => now(),
                ]);
            });
        }

        return response()->json(['status' => 'ok']);
    }
}
