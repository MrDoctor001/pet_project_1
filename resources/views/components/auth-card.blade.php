@props(['title', 'subtitle' => null])

<div class="mx-auto flex w-full max-w-md flex-col gap-8 py-6">
    <div class="flex flex-col items-center gap-2 text-center">
        <span class="flex size-12 items-center justify-center rounded-2xl bg-brand-600 text-white shadow-lg shadow-brand-600/30">
            <svg class="size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z" />
            </svg>
        </span>
        <h1 class="text-3xl font-extrabold tracking-tight">{{ $title }}</h1>
        @if ($subtitle)
            <p class="text-zinc-500">{{ $subtitle }}</p>
        @endif
    </div>

    <div class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8">
        {{ $slot }}
    </div>

    @isset($footer)
        <p class="text-center text-sm text-zinc-500">{{ $footer }}</p>
    @endisset
</div>
