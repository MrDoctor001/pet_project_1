@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">

    <title>{{ $title ? $title.' — Админка' : 'Админка' }} — {{ config('app.name') }}</title>

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-canvas font-sans text-zinc-900 antialiased selection:bg-brand-200">
    <aside class="fixed inset-y-0 left-0 hidden w-64 flex-col bg-ink-950 px-4 py-6 text-zinc-300 lg:flex">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2.5 px-2 text-lg font-extrabold tracking-tight text-white">
            <span class="flex size-9 items-center justify-center rounded-xl bg-brand-500 text-white shadow-lg shadow-brand-500/30">
                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                </svg>
            </span>
            {{ config('app.name') }}
            <span class="rounded-md bg-white/10 px-1.5 py-0.5 text-[11px] font-bold tracking-wide text-brand-300 uppercase">Админ</span>
        </a>

        <x-admin.nav class="mt-8 flex-1" />

        <div class="flex items-center justify-between gap-3 border-t border-white/10 px-2 pt-5 text-sm">
            <span class="truncate text-zinc-400">{{ auth()->user()->name }}</span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="rounded-lg px-2 py-1 font-semibold transition hover:bg-white/5 hover:text-white">Выйти</button>
            </form>
        </div>
    </aside>

    <header class="sticky top-0 z-20 bg-ink-950/95 text-zinc-300 backdrop-blur lg:hidden">
        <div class="flex h-16 items-center justify-between px-4 sm:px-6">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2 text-lg font-extrabold tracking-tight text-white">
                {{ config('app.name') }}
                <span class="rounded-md bg-white/10 px-1.5 py-0.5 text-[11px] font-bold tracking-wide text-brand-300 uppercase">Админ</span>
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

                <div class="absolute inset-x-0 top-16 border-t border-white/10 bg-ink-950 px-4 pt-2 pb-4 shadow-2xl">
                    <x-admin.nav class="text-base" />

                    <form method="POST" action="{{ route('logout') }}" class="mt-1">
                        @csrf
                        <button type="submit" class="flex w-full rounded-xl px-3 py-2.5 text-base font-semibold transition hover:bg-white/5 hover:text-white">Выйти</button>
                    </form>
                </div>
            </details>
        </div>
    </header>

    <main class="flex min-h-screen flex-col gap-6 px-4 py-8 sm:px-6 lg:ml-64 lg:px-10 lg:py-10">
        @if (session('status'))
            <div role="status" class="flex items-center gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-semibold text-emerald-800">
                <svg class="size-5 shrink-0 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if ($title || isset($actions))
            <div class="flex flex-wrap items-center justify-between gap-4">
                @if ($title)
                    <h1 class="text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $title }}</h1>
                @endif

                @isset($actions)
                    <div class="flex flex-wrap items-center gap-3">{{ $actions }}</div>
                @endisset
            </div>
        @endif

        {{ $slot }}
    </main>
</body>
</html>
