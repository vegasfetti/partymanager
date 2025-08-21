<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function send(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|min:2|max:50',
                'email' => 'required|email|max:100',
                'phone' => 'nullable|string|max:30',
                'telegram' => 'nullable|string|max:50',
                'message' => 'required|string|min:5|max:1000',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withInput()
            ->withFragment('footer')->with('alerts', [
                ['type' => 'error', 'message' => $e->validator->errors()->first()],
            ]);;
        }

        $userId = auth()->id();

        $query = Feedback::query()
            ->whereDate('created_at', Carbon::today());

        if ($userId) {
            $query->where('user_id', $userId);
        } else {
            $query->where('email', $data['email']);
        }

        if ($query->exists()) {
            return back()
                ->withFragment('footer')->with('alerts', [
                        ['type' => 'info', 'message' => 'Вы уже оставляли обращение сегодня, повторите попытку завтра'],
                    ]);
        }

        Feedback::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $data['phone'] ?? null,
            'telegram' => $data['telegram'] ?? null,
            'message' => $data['message'],
            'user_id' => $userId,
        ]);

        return back()
            ->withFragment('footer')->with('alerts', [
                ['type' => 'success', 'message' => 'Спасибо за обращение, мы свяжемся с вами в ближайшее время'],
            ]);
    }
}
