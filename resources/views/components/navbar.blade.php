<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            Lezioni Informatica
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            {{-- Center links --}}
            <ul class="navbar-nav mx-auto gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('theme-areas.index') }}">Aree Tematiche</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lesson-requests.create') }}">Materiale su richiesta</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">Informazioni</a>
                </li>
            </ul>

            {{-- Right side --}}
            <ul class="navbar-nav ms-auto align-items-center gap-2">

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                            Registrati
                        </a>
                    </li>
                @endguest

                @auth

                    @if (auth()->user()->role === 'student')
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.show') }}">
                                <i class="bi bi-cart3 fs-5"></i>

                                @php
                                    $cart = session()->get('cart');
                                    $count = $cart instanceof \App\Http\Utility\Cart ? $cart->count() : 0;
                                @endphp

                                @if ($count > 0)
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $count }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" style="cursor: pointer;">
                            {{ auth()->user()->name }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item"
                                    href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}">
                                    Area personale
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="{{ route('logout') }}">
                                    Logout
                                </a>
                            </li>

                        </ul>
                    </li>

                @endauth

            </ul>

        </div>
    </div>
</nav>
