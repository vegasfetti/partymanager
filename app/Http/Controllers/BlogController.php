<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class BlogController extends Controller
{


    public function index(){

        $blogs = Blog::all()->sortByDesc('created_at');

        return view('pages.blog.index', compact('blogs'));
    }
    public function show($slug){

        $blog = Blog::where('slug', $slug)->firstOrFail();

        return view('pages.blog.show', compact('blog'));
    }
}
