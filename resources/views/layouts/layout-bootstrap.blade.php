<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#077a9f">

    <title>Lezioni Informatica</title>

    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Icons --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        html {
            overflow-y: scroll;
            /* scrollbar-gutter: stable; */
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: #f6f7fb;
        }

        .app-shell {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        main {
            flex: 1;
        }

        :root {
            --app-primary: #077a9f;
            --app-primary-rgb: 7, 122, 159;
            --app-primary-hover: #076b8c;
            --app-primary-active: #05556f;

            --bs-primary: var(--app-primary);
            --bs-primary-rgb: var(--app-primary-rgb);
            --bs-link-color: var(--app-primary);
            --bs-link-hover-color: var(--app-primary-hover);
        }

        .btn-primary {
            --bs-btn-color: #fff;
            --bs-btn-bg: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary-hover);
            --bs-btn-hover-border-color: var(--app-primary-hover);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-active);
            --bs-btn-active-border-color: var(--app-primary-active);
            --bs-btn-disabled-color: #fff;
            --bs-btn-disabled-bg: var(--app-primary);
            --bs-btn-disabled-border-color: var(--app-primary);
            --bs-btn-focus-shadow-rgb: var(--app-primary-rgb);
        }

        .btn-outline-primary {
            --bs-btn-color: var(--app-primary);
            --bs-btn-border-color: var(--app-primary);
            --bs-btn-hover-color: #fff;
            --bs-btn-hover-bg: var(--app-primary);
            --bs-btn-hover-border-color: var(--app-primary);
            --bs-btn-active-color: #fff;
            --bs-btn-active-bg: var(--app-primary-active);
            --bs-btn-active-border-color: var(--app-primary-active);
            --bs-btn-focus-shadow-rgb: var(--app-primary-rgb);
        }
    </style>
    @stack('styles')

</head>

<body>

    <div class="app-shell">

        <x-navbar />

        <main class="@yield('main-class', 'container pb-4')">
            @yield('content')
        </main>

        <x-footer />

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')

    {{-- Load Echo only when required. --}}
    @if (isset($enableEcho) && $enableEcho)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://unpkg.com/laravel-echo@1.16.1/dist/echo.iife.js"></script>

        <script>
            window.Pusher = Pusher;

            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: @json(config('broadcasting.connections.reverb.key')),
                wsHost: @json(config('broadcasting.connections.reverb.options.host')),
                wsPort: @json((int) config('broadcasting.connections.reverb.options.port')),
                wssPort: @json((int) config('broadcasting.connections.reverb.options.port')),
                forceTLS: @json(config('broadcasting.connections.reverb.options.scheme') === 'https'),
                enabledTransports: ['ws', 'wss'],
            });
        </script>
    @endif

</body>

</html>
