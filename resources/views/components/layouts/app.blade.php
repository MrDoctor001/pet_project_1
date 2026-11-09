@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen flex-col bg-canvas font-sans text-zinc-900 antialiased selection:bg-brand-200">
    <header class="sticky top-0 z-20 bg-ink-950/95 text-zinc-300 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 text-lg font-extrabold tracking-tight text-white">
                <span class="flex size-9 items-center justify-center rounded-xl bg-brand-500 text-white shadow-lg shadow-brand-500/30">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                    </svg>
                </span>
                {{ config('app.name') }}
            </a>

            @php($cartCount = app(\App\Services\Cart::class)->count())

            <nav class="hidden items-center gap-2 text-sm font-semibold sm:flex">
                <a
                    href="{{ route('catalog.index') }}"
                    @class([
                        'rounded-full px-4 py-2 transition hover:text-white',
                        'bg-white/10 text-white' => request()->routeIs('catalog.*'),
                    ])
                >
                    Каталог
                </a>

                <a
                    href="{{ route('cart.index') }}"
                    @class([
                        'flex items-center gap-2 rounded-full px-4 py-2 transition hover:text-white',
                        'bg-white/10 text-white' => request()->routeIs('cart.*', 'checkout.*'),
                    ])
                >
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>
                    Корзина

                    @if ($cartCount > 0)
                        <span class="flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-500 px-1.5 text-xs font-bold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                @auth
                    @if (auth()->user()->is_admin && Route::has('admin.dashboard'))
                        <a href="{{ route('admin.dashboard') }}" class="rounded-full px-4 py-2 text-brand-300 transition hover:text-white">
                            Админка
                        </a>
                    @endif

                    @if (Route::has('profile.edit'))
                        <a
                            href="{{ route('profile.edit') }}"
                            @class([
                                'flex items-center gap-2 rounded-full px-4 py-2 transition hover:text-white',
                                'bg-white/10 text-white' => request()->routeIs('profile.*'),
                            ])
                        >
                            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
                            </svg>
                            <span class="max-w-32 truncate">{{ auth()->user()->name }}</span>
                        </a>
                    @endif

                    @if (Route::has('logout'))
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="rounded-full px-4 py-2 transition hover:bg-white/10 hover:text-white">
                                Выйти
                            </button>
                        </form>
                    @endif
                @else
                    @if (Route::has('login'))
                        <a
                            href="{{ route('login') }}"
                            class="rounded-full bg-brand-600 px-4 py-2 text-white shadow-lg shadow-brand-600/30 transition hover:bg-brand-500"
                        >
                            Войти
                        </a>
                    @endif
                @endauth
            </nav>

            <div class="flex items-center gap-1 sm:hidden">
                <a
                    href="{{ route('cart.index') }}"
                    aria-label="Корзина"
                    @class([
                        'relative flex size-11 items-center justify-center rounded-full transition hover:text-white',
                        'bg-white/10 text-white' => request()->routeIs('cart.*', 'checkout.*'),
                    ])
                >
                    <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                    </svg>

                    @if ($cartCount > 0)
                        <span class="absolute top-0.5 right-0.5 flex h-5 min-w-5 items-center justify-center rounded-full bg-brand-500 px-1.5 text-xs font-bold text-white">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>

                <details class="group" data-menu>
                    <summary
                        aria-label="Меню"
                        class="flex size-11 cursor-pointer list-none items-center justify-center rounded-full transition group-open:bg-white/10 group-open:text-white hover:text-white [&::-webkit-details-marker]:hidden"
                    >
                        <svg class="size-6 group-open:hidden" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                        </svg>
                        <svg class="hidden size-6 group-open:block" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </summary>

                    <nav class="absolute inset-x-0 top-16 border-t border-white/10 bg-ink-950 px-4 pt-2 pb-4 shadow-2xl">
                        <ul class="flex flex-col gap-1 text-base font-semibold">
                            <li>
                                <a
                                    href="{{ route('catalog.index') }}"
                                    @class([
                                        'flex rounded-xl px-3 py-3 transition hover:bg-white/5 hover:text-white',
                                        'bg-white/10 text-white' => request()->routeIs('catalog.*'),
                                    ])
                                >
                                    Каталог
                                </a>
                            </li>

                            @auth
                                @if (auth()->user()->is_admin && Route::has('admin.dashboard'))
                                    <li>
                                        <a href="{{ route('admin.dashboard') }}" class="flex rounded-xl px-3 py-3 text-brand-300 transition hover:bg-white/5 hover:text-white">
                                            Админка
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('profile.edit'))
                                    <li>
                                        <a
                                            href="{{ route('profile.edit') }}"
                                            @class([
                                                'flex items-center justify-between gap-3 rounded-xl px-3 py-3 transition hover:bg-white/5 hover:text-white',
                                                'bg-white/10 text-white' => request()->routeIs('profile.*'),
                                            ])
                                        >
                                            Профиль
                                            <span class="truncate text-sm font-normal text-zinc-500">{{ auth()->user()->name }}</span>
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('logout'))
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="flex w-full rounded-xl px-3 py-3 transition hover:bg-white/5 hover:text-white">
                                                Выйти
                                            </button>
                                        </form>
                                    </li>
                                @endif
                            @else
                                @if (Route::has('register'))
                                    <li>
                                        <a href="{{ route('register') }}" class="flex rounded-xl px-3 py-3 transition hover:bg-white/5 hover:text-white">
                                            Регистрация
                                        </a>
                                    </li>
                                @endif

                                @if (Route::has('login'))
                                    <li class="pt-2">
                                        <a
                                            href="{{ route('login') }}"
                                            class="flex justify-center rounded-xl bg-brand-600 px-3 py-3 text-white shadow-lg shadow-brand-600/30 transition hover:bg-brand-500"
                                        >
                                            Войти
                                        </a>
                                    </li>
                                @endif
                            @endauth
                        </ul>
                    </nav>
                </details>
            </div>
        </div>
    </header>

    {{-- Необязательный блок на всю ширину экрана сразу под шапкой --}}
    @isset($hero)
        {{ $hero }}
    @endisset

    <main class="mx-auto flex w-full max-w-7xl flex-1 flex-col gap-6 px-4 py-10 sm:px-6 lg:px-8">
        @if (session('status'))
            <div role="status" class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        {{ $slot }}
    </main>

    <footer class="mt-10 bg-ink-950 text-zinc-400">
        <div class="mx-auto grid max-w-7xl gap-10 px-4 py-12 sm:grid-cols-2 sm:px-6 lg:grid-cols-4 lg:px-8">
            <div class="flex flex-col gap-3 lg:col-span-2">
                <p class="text-lg font-extrabold tracking-tight text-white">{{ config('app.name') }}</p>
                <p class="max-w-sm text-sm leading-relaxed">
                    Техника, одежда, книги, игрушки, мебель и товары для спорта — с доставкой по всему Казахстану и оплатой при получении.
                </p>
            </div>

            <div class="flex flex-col gap-3 text-sm">
                <p class="font-semibold text-white">Покупателям</p>
                <a href="{{ route('catalog.index') }}" class="transition hover:text-brand-300">Каталог</a>
                <a href="{{ route('cart.index') }}" class="transition hover:text-brand-300">Корзина</a>
            </div>

            <div class="flex flex-col gap-3 text-sm">
                <p class="font-semibold text-white">Контакты</p>
                <a href="tel:+77712440236" class="transition hover:text-brand-300">8 (771) 244-02-36</a>
                <p>Алматы, ежедневно с 9:00 до 21:00</p>
            </div>
        </div>

        <div class="border-t border-white/10">
            <p class="mx-auto max-w-7xl px-4 py-5 text-xs text-zinc-500 sm:px-6 lg:px-8">
                &copy; {{ date('Y') }} {{ config('app.name') }}
            </p>
        </div>
    </footer>
</body>
</html>
