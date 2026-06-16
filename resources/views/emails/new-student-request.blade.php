<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
</head>

<body>
    <h2>{{ __('mail.new_student_request.title') }}</h2>

    <p>{{ __('mail.new_student_request.greeting') }}</p>

    <p>{{ __('mail.new_student_request.body') }}</p>

    <p><strong>{{ __('mail.new_student_request.cta') }}</strong></p>

    <br>
    <p>{{ __('mail.signature') }}</p>
</body>

</html>
