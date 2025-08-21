<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Blog;
use App\Models\Category;
use App\Models\Review;
use Illuminate\Http\Request;

class MainController extends Controller
{
    public function index()
    {



        $current_city = app('current_city');


        // gets
        $categories = Category::limit(6)->get();

        $blogs = Blog::limit(3)->get();


        $banners = Banner::where('status', true)
            ->where('type', 'main')
            ->where(function ($query) use ($current_city) {
                $query->whereNull('city_id')
                    ->orWhere('city_id', $current_city->id);
            })
            ->get();


        $reviews = Review::where('status', 'verify')
            ->where('rating', '>=', 4)->orderBy('created_at', 'desc')
            ->limit(10)->get();


        return view('pages.main', compact('categories', 'blogs', 'banners', 'current_city', 'reviews'));
    }
}
