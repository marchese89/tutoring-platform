<nav class="navbar navbar-expand-lg bg-white border-bottom sticky-top">
    <div class="container">

        <a class="navbar-brand fw-bold" href="{{ route('home') }}">
            {{ __('navigation.brand') }}
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="nav">

            <ul class="navbar-nav mx-auto gap-2">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}">{{ __('navigation.home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('theme-areas.index') }}">{{ __('navigation.theme_areas') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('lesson-requests.create') }}">{{ __('navigation.custom_material') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('about') }}">{{ __('navigation.about') }}</a>
                </li>
            </ul>

            <ul class="navbar-nav ms-auto align-items-center gap-2">
                <li class="nav-item dropdown">
                    <button class="nav-link dropdown-toggle border-0 bg-transparent" type="button"
                        data-bs-toggle="dropdown" aria-label="{{ __('navigation.language') }}">
                        <i class="bi bi-globe2" aria-hidden="true"></i>
                        <span class="ms-1">{{ config('localization.locales.' . app()->getLocale()) }}</span>
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end">
                        @foreach (config('localization.locales') as $locale => $label)
                            <li>
                                <form method="POST" action="{{ route('locale.update') }}">
                                    @csrf
                                    <input type="hidden" name="locale" value="{{ $locale }}">
                                    <button type="submit"
                                        class="dropdown-item @if (app()->isLocale($locale)) active @endif"
                                        @if (app()->isLocale($locale)) aria-current="true" @endif>
                                        {{ $label }}
                                    </button>
                                </form>
                            </li>
                        @endforeach
                    </ul>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('navigation.login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                            {{ __('navigation.register') }}
                        </a>
                    </li>
                @endguest

                @auth

                    @if (auth()->user()->role === 'student')
                        <li class="nav-item">
                            <a class="nav-link position-relative" href="{{ route('cart.show') }}"
                                aria-label="{{ trans_choice('navigation.cart', $cartCount, ['count' => $cartCount]) }}">
                                <i class="bi bi-cart3 fs-5"></i>

                                @if ($cartCount > 0)
                                    <span class="badge bg-primary rounded-pill">
                                        {{ $cartCount }}
                                    </span>
                                @endif
                            </a>
                        </li>
                    @endif

                    <li class="nav-item dropdown">
                        <button class="nav-link dropdown-toggle border-0 bg-transparent" type="button"
                            data-bs-toggle="dropdown">
                            {{ auth()->user()->name }}
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end">

                            <li>
                                <a class="dropdown-item"
                                    href="{{ auth()->user()->role === 'admin' ? route('admin.dashboard') : route('student.dashboard') }}">
                                    {{ __('navigation.personal_area') }}
                                </a>
                            </li>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        {{ __('navigation.logout') }}
                                    </button>
                                </form>
                            </li>

                        </ul>
                    </li>

                @endauth

            </ul>

        </div>
    </div>
</nav>
