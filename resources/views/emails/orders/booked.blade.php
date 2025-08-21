@component('mail::message')
# Вам пришла заявка на бронирование

Здравствуйте, {{ $order->specialist->user->name ?? 'Исполнитель' }}!  
Вам пришла новая заявка на бронирование.

**От:** {{ $order->user->name ?? 'Неизвестный пользователь' }}  <br>
**Телефон:** {{ $order->phone }}  <br>
@if($order->comment)
**Комментарий:** {{ $order->comment }}<br>
@endif
**Дата отправки:** {{ $order->created_at ->format('d.m.Y H:i') }}<br>

@component('mail::button', ['url' => route('profile')])
Посмотреть заявку
@endcomponent

С уважением,<br>
{{ config('app.name') }}
@endcomponent
