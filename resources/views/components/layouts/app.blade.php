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
<body class="flex min-h-screen flex-col bg-gray-50 font-sans text-gray-900 antialiased">
    <header class="sticky top-0 z-10 border-b border-gray-200 bg-white/80 backdrop-blur">
        <div class="mx-auto flex h-16 max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('catalog.index') }}" class="flex items-center gap-2.5 text-lg font-semibold tracking-tight">
                <span class="flex size-9 items-center justify-center rounded-xl bg-indigo-600 text-white shadow-sm shadow-indigo-600/30">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007Z" />
                    </svg>
                </span>
                {{ config('app.name') }}
            </a>

            <nav class="flex items-center gap-6 text-sm font-medium">
                <a
                    href="{{ route('catalog.index') }}"
                    @class([
                        'transition hover:text-gray-900',
                        'text-gray-900' => request()->routeIs('catalog.*'),
                        'text-gray-500' => ! request()->routeIs('catalog.*'),
                    ])
                >
                    Каталог
                </a>
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-10 sm:px-6 lg:px-8">
        {{ $slot }}
    </main>

    <footer class="border-t border-gray-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-1 px-4 py-6 text-sm text-gray-500 sm:flex-row sm:justify-between sm:px-6 lg:px-8">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
            <p>Учебный проект на Laravel</p>
        </div>
    </footer>
</body>
</html>
