<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <title>{{ __('mail.reset_password.subject') }}</title>
</head>

<body>

    <h2>{{ __('mail.reset_password.title') }}</h2>

    <p>{{ __('mail.reset_password.greeting') }}</p>

    <p>{{ __('mail.reset_password.body') }}</p>

    <p>
        <a href="{{ $url }}"
            style="padding:10px 20px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px;hover:bg-blue-700;">
            {{ __('mail.reset_password.action') }}
        </a>
    </p>

    <p>{{ __('mail.reset_password.expires') }}</p>

    <p>{{ __('mail.reset_password.ignore') }}</p>

    <br>

    <p>{{ __('mail.reset_password.regards') }}<br>{{ __('mail.signature') }}</p>

    <hr>

    <p>
        {{ __('mail.reset_password.fallback', ['action' => __('mail.reset_password.action')]) }}
    </p>

    <p>
        <a href="{{ $url }}">{{ $url }}</a>
    </p>

</body>

</html>
