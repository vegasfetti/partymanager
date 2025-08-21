<?php

use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\SmartOrderController;
use App\Http\Controllers\SpecialistController;
use App\Http\Controllers\СontactController;
use App\Models\Review;
use Illuminate\Support\Facades\Route;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



/*
    set city form cookie
*/
Route::get('/set-city/{slug}', function ($slug) {
    $city = \App\Models\City::where('slug', $slug)->firstOrFail();
    return redirect()->back()->withCookie(cookie('city_slug', $slug, 60 * 24 * 30));
})->name('set.city');


/*
    main
*/

Route::get('/', [MainController::class, 'index'])->name('main');



/*
    categories page
*/

Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');




/*
    specialists
*/
// Create specialist

Route::get('/specialist/create', [SpecialistController::class, 'createPage'])
    ->middleware('auth', 'verified')
    ->name('specialists.create');

Route::post('/specialist/create', [SpecialistController::class, 'createAction'])
    ->middleware('auth', 'verified')
    ->name('specialists.create.action');


// other
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/specialist/{specialist}/edit', [SpecialistController::class, 'edit'])->name('specialists.edit');
    Route::put('/specialist/{specialist}', [SpecialistController::class, 'update'])->name('specialists.update');
    Route::delete('/specialist/{specialist}', [SpecialistController::class, 'destroy'])->name('specialists.destroy');
});

Route::get('/specialists', [SpecialistController::class, 'index'])->name('specialists');

Route::get('/specialists/{category}', [SpecialistController::class, 'index'])->name('specialists.byCategory');

Route::get('/specialists/{category}/{subcategory}', [SpecialistController::class, 'index'])->name('specialists.bySubcategory');


Route::get('/specialist/{id}', [SpecialistController::class, 'show'])
    ->name('specialists.show');








/*
    services
*/
// create page
Route::get('/specialist/{id}/service', [ServiceController::class, 'createPage'])->name('service.create')->middleware('auth', 'verified');
// create action
Route::post('/specialist/{id}/service', [ServiceController::class, 'createAction'])->name('service.create.action')->middleware('auth', 'verified');

// delete
Route::delete('/service/{id}', [ServiceController::class, 'deleteService'])->name('services.destroy')->middleware('auth', 'verified');




/*
    reviews
*/

// create
Route::post('/reviews/create', action: [ReviewController::class, 'create'])->name('reviews.create')->middleware('auth', 'verified');

/*
    auth
*/



Route::prefix('auth')->group(function () {

    // pages
    Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup')->middleware('guest');
    Route::get('/signin', [AuthController::class, 'showSignin'])->name('signin')->middleware('guest');


    // actions
    Route::post('/signup', [AuthController::class, 'actionSignup'])->name('action.signup');
    Route::post('/signin', [AuthController::class, 'actionSignin'])->name('action.signin');
    Route::get('/signout', [AuthController::class, 'actionSignout'])->name('signout');

})->middleware(['guest']);




////
// verification email
////

// Страница с просьбой подтвердить почту
Route::get('/email/verify', function () {
    return view('pages.auth.verify-email');
})->middleware('auth')->name('verification.notice');


// Сама ссылка для подтверждения
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // <- подтверждение email
    return redirect(route('specialists')); // Куда отправить после подтверждения
})->middleware(['auth', 'signed'])->name('verification.verify');


// Повторная отправка письма
Route::post('/email/verification-notification', function (Request $request) {
    try {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('alerts', [
            ['type' => 'success', 'message' => 'Письмо повторно отправлено']
        ]);
    } catch (Throwable $e) {
        return back()->with('alerts', [
            ['type' => 'error', 'message' => 'Ошибка отправки письма']
        ]);
    }
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');



////
// password reset
////

// Форма ввода email
Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotForm'])
    ->middleware('guest')
    ->name('password.request');

// Обработка запроса на восстановление
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])
    ->middleware('guest', 'throttle:6,1')
    ->name('password.email');

// Форма установки нового пароля
Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->middleware('guest')
    ->name('password.reset');

// Обработка сброса пароля
Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])
    ->middleware('guest')
    ->name('password.update');







/*
    profile
*/
Route::get('/profile', [ProfileController::class, 'index'])->middleware('auth', 'verified')->name('profile');
Route::get('profile/edit', [ProfileController::class, 'edit'])->middleware('auth', 'verified')->name('profile.edit');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::put('/profile/update', [ProfileController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/change-password', [ProfileController::class, 'changePassword'])->name('profile.changePassword');
    Route::delete('/profile/delete', [ProfileController::class, 'deleteAccount'])->name('profile.delete');
});



/*
    simple-order
*/

// create
Route::post('/send-order', [OrderController::class, 'sendOrder'])->name('sendOrder')->middleware('auth', 'verified');
// delete
Route::delete('/order/{id}', [OrderController::class, 'deleteOrder'])->name('orders.destroy')->middleware('auth', 'verified');
// confirm
Route::put('/order/{id}', [OrderController::class, 'orderConfirm'])->name('orders.confirm')->middleware('auth', 'verified');
// canceled
Route::put('/order/{id}/cancel', [OrderController::class, 'orderCancel'])->name('orders.cancel')->middleware('auth', 'verified');


/*
    cart
*/

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/booking', [CartController::class, 'index'])->name('cart.index');
    Route::post('/booking/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/booking/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/booking/clear', [CartController::class, 'clear'])->name('cart.clear');
});

/*
    smart-order
*/
Route::post('/booking/create', [SmartOrderController::class, 'add'])->name('smartorder.create')->middleware('auth', 'verified');







/*
    blog
*/

Route::get('/blog/{slug}', [BlogController::class, 'show'])->name(name: 'blog.show');
Route::get('/blog', [BlogController::class, 'index'])->name(name: 'blog');




/*
    away
*/

Route::get('/away', function () {
    return view('pages.away.index');
})->name(name: 'away');


/*
    docs
*/

//privacy

Route::group(['prefix' => 'docs'], function () {
    Route::get('/privacy', function () {
        return view('pages.docs.privacy');
    })->name(name: 'privacy');
    Route::get('/cookie', function () {
        return view('pages.docs.cookie');
    })->name(name: 'cookie');
    Route::get('/offer', function () {
        return view(view: 'pages.docs.offer');
    })->name(name: 'offer');
});

/*
    request
*/
Route::group(['prefix' => 'request'], function () {
    Route::post('/promo/{id}', [RequestController::class, 'promo'])->name('request.promo')->middleware('auth', 'verified');
    Route::post('/verification/{id}', [RequestController::class, 'verification'])->name('request.verification')->middleware('auth', 'verified');
});

Route::post('/form-feedback', [FeedbackController::class, 'send'])->name('form.feedback')->middleware('auth', 'verified');


// yookassa
Route::post('/yookassa/webhook', [PaymentController::class, 'webhook']);