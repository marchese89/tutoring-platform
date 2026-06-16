<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <h2>{{ __('mail.lesson_request_fulfilled.title') }}</h2>

    <p>{{ __('mail.lesson_request_fulfilled.greeting') }}</p>

    <p>{{ __('mail.lesson_request_fulfilled.body') }}</p>

    <p>{{ __('mail.lesson_request_fulfilled.cta') }}</p>

    <br>
    <p>{{ __('mail.signature') }}</p>
</body>

</html>
