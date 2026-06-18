<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('mail.order_completed.subject') }}</title>
</head>

<body style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">

    <h2>{{ __('mail.order_completed.title', ['name' => $user->name, 'surname' => $user->surname]) }}</h2>

    <p>
        {{ __('mail.order_completed.body') }}
    </p>

    <p>
        {{ __('mail.order_completed.invoice_attached') }}
    </p>

    <br>

    <p>
        {{ __('mail.order_completed.details') }}
    </p>

    <ul>
        <li><b>{{ __('mail.order_completed.date') }}:</b> {{ $data }}</li>
        <li><b>{{ __('mail.order_completed.total') }}:</b> {{ $total }} &euro;</li>
    </ul>

    <br>

    <p>
        {{ __('mail.order_completed.thanks') }}
        <br>
        <br>
        <br>
        <br>
        <br>
        <b>{{ __('mail.signature') }}</b>
    </p>

</body>

</html>
