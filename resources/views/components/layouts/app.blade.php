@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $title ? $title.' — '.config('app.name') : config('app.name') }}</title>

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

            <nav class="flex items-center gap-2 text-sm font-semibold">
                <a
                    href="{{ route('catalog.index') }}"
                    @class([
                        'rounded-full px-4 py-2 transition hover:text-white',
                        'bg-white/10 text-white' => request()->routeIs('catalog.*'),
                    ])
                >
                    Каталог
                </a>

                @php($cartCount = app(\App\Services\Cart::class)->count())

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
            </nav>
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
                <a href="tel:+77270000000" class="transition hover:text-brand-300">+7 (727) 000-00-00</a>
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
