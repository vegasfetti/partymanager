<?php

namespace App\Http\Middleware;

use App\Models\City;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetCityFromCookie
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {


        $slug = $request->cookie('city_slug');

        $city = City::where('slug', $slug)->first();

        // Если куки нет или город не найден — берем дефолтный
        if (!$city) {
            $city = City::where('id', '2')->first(); // или любой другой
        }

        // Делаем город глобально доступным
        app()->instance('current_city', $city);
        \Illuminate\Support\Facades\View::share('current_city', $city);

        return $next($request);
    }
}
