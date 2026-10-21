@props(['categories', 'current' => null])

@php
    $linkClasses = 'block whitespace-nowrap rounded-full border px-4 py-2 text-sm font-medium transition';
    $activeClasses = 'border-brand-600 bg-brand-600 text-white shadow-sm shadow-brand-600/20';
    $inactiveClasses = 'border-zinc-200 bg-white text-zinc-600 hover:border-zinc-300 hover:text-zinc-900';
@endphp

<nav aria-label="Категории" class="-mx-4 overflow-x-auto px-4 pb-1 sm:mx-0 sm:px-0">
    <ul class="flex gap-2 sm:flex-wrap">
        <li>
            <a
                href="{{ route('catalog.index') }}"
                @class([$linkClasses, $activeClasses => $current === null, $inactiveClasses => $current !== null])
                @if ($current === null) aria-current="page" @endif
            >
                Все товары
            </a>
        </li>

        @foreach ($categories as $category)
            @php($isActive = $current?->is($category) ?? false)

            <li>
                <a
                    href="{{ route('catalog.category', $category) }}"
                    @class([$linkClasses, $activeClasses => $isActive, $inactiveClasses => ! $isActive])
                    @if ($isActive) aria-current="page" @endif
                >
                    {{ $category->name }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
