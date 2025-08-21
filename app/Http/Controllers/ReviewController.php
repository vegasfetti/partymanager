<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Specialist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function create(Request $request) {
    $validated = $request->validate([
        'title' => 'required|string|max:100|min:3',
        'text' => 'required|string|max:255|min:10',
        'rating' => 'required|integer|min:1|max:5',
        'specialist_id' => 'required|exists:specialists,id',
    ]);

    if(Auth::id() == Specialist::find($validated['specialist_id'])->user_id) {
        return back()->with('alerts', [
            ['type' => 'error', 'message' => 'Вы не можете оставить отзыв самому себе!'],
        ]);
    }

    try {
        $review = Review::create([
            'title' => $validated['title'],
            'text' => $validated['text'],
            'rating' => $validated['rating'],
            'specialist_id' => $validated['specialist_id'],
            'user_id' => Auth::id(),
            'status' => 'on_moderation',
        ]);


        return back()->with('alerts', [
        ['type' => 'success', 'message' => 'Отзыв отправлен на модерацию!'],
        ]);

    } catch (\Exception $e) {
        return back()->withInput()->with('error', 'Повторите попытку или попробуйте позже!');
    }
}
}
