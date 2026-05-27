<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Lezioni Informatica</title>



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
            /* 1. Definisci qui i tuoi colori personalizzati una sola volta */
            --my-primary: #22c4ff;
            --my-primary-rgb: 255, 87, 34;
            --my-primary-hover: #19e3e6;
            /* Tono più scuro per l'effetto hover */
            --my-primary-active: #15d8c8;
            /* Tono ancora più scuro per il click */

            /* 2. Mappa le variabili globali di Bootstrap */
            --bs-primary: var(--my-primary);
            --bs-primary-rgb: var(--my-primary-rgb);
            --bs-link-color: var(--my-primary);
            --bs-link-hover-color: var(--my-primary-hover);
        }

        /* 3. Aggiorna il bottone pieno (.btn-primary) */
        .btn-primary {
            --bs-btn-bg: var(--my-primary);
            --bs-btn-border-color: var(--my-primary);
            --bs-btn-hover-bg: var(--my-primary-hover);
            --bs-btn-hover-border-color: var(--my-primary-hover);
            --bs-btn-active-bg: var(--my-primary-active);
            --bs-btn-active-border-color: var(--my-primary-active);
            --bs-btn-focus-shadow-rgb: var(--my-primary-rgb);
            --bs-btn-disabled-bg: var(--my-primary);
            --bs-btn-disabled-border-color: var(--my-primary);
        }

        /* 4. Aggiorna il bottone outline (.btn-outline-primary) */
        .btn-outline-primary {
            --bs-btn-color: var(--my-primary);
            --bs-btn-border-color: var(--my-primary);
            --bs-btn-hover-bg: var(--my-primary);
            --bs-btn-hover-border-color: var(--my-primary);
            --bs-btn-active-bg: var(--my-primary);
            --bs-btn-active-border-color: var(--my-primary);
            --bs-btn-focus-shadow-rgb: var(--my-primary-rgb);
        }
    </style>

</head>

<body>

    <div class="app-shell">

        <x-navbar />

        <main class="container pb-4">
            @yield('content')
        </main>

        <x-footer />

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="./custom_javascript/utility.js"></script>

    {{-- Echo (solo se serve) --}}
    @if (isset($enableEcho) && $enableEcho)
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script src="https://unpkg.com/laravel-echo@1.16.1/dist/echo.iife.js"></script>

        <script>
            window.Pusher = Pusher;

            window.Echo = new Echo({
                broadcaster: 'reverb',
                key: 'local',
                wsHost: window.location.hostname,
                wsPort: 8080,
                forceTLS: false,
                enabledTransports: ['ws', 'wss'],
            });
        </script>
    @endif

</body>

</html>
