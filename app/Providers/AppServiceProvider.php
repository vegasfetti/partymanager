<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageServiceProvider;
use Intervention\Image\Facades\Image;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function ($notifiable, $url) {
            return (new MailMessage)
                ->subject('Подтверждение Email')
                ->greeting('Здравствуйте!')
                ->line('Вы зарегистрировались на сайте ПАТИМЕНЕДЖЕР (partymanager.ru).')
                ->line('Для подтверждения почты нажмите кнопку ниже.')
                ->action('Подтвердить', $url)
                ->line('Если вы не регистрировались, просто проигнорируйте это письмо.');
        });
    }
}
