@php
    $pageClasses = 'flex size-10 items-center justify-center rounded-full text-sm font-semibold transition';
    $arrowClasses = 'flex size-10 items-center justify-center rounded-full bg-white text-zinc-700 shadow-sm ring-1 ring-zinc-900/5 transition hover:bg-brand-50 hover:text-brand-700';
    $disabledArrowClasses = 'flex size-10 items-center justify-center rounded-full bg-white/60 text-zinc-300 ring-1 ring-zinc-900/5 cursor-not-allowed';
@endphp

@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Постраничная навигация" class="flex flex-col items-center gap-4 sm:flex-row sm:justify-between">
        <p class="text-sm text-zinc-500">
            Показано
            <span class="font-semibold text-zinc-900">{{ $paginator->firstItem() }}–{{ $paginator->lastItem() }}</span>
            из
            <span class="font-semibold text-zinc-900">{{ $paginator->total() }}</span>
        </p>

        <div class="flex items-center gap-1.5 rounded-full bg-white/70 p-1.5 shadow-sm ring-1 ring-zinc-900/5 backdrop-blur">
            @if ($paginator->onFirstPage())
                <span class="{{ $disabledArrowClasses }}" aria-disabled="true" aria-label="Предыдущая страница">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="{{ $arrowClasses }}" aria-label="Предыдущая страница">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </a>
            @endif

            {{-- На телефоне вместо номеров показываем «2 / 3» --}}
            <span class="px-3 text-sm font-semibold text-zinc-700 sm:hidden">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            <div class="hidden items-center gap-1.5 sm:flex">
                @foreach ($elements as $element)
                    {{-- Строка — это разделитель «...» между далёкими страницами --}}
                    @if (is_string($element))
                        <span class="{{ $pageClasses }} text-zinc-400" aria-disabled="true">{{ $element }}</span>
                    @endif

                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span class="{{ $pageClasses }} bg-brand-600 text-white shadow-md shadow-brand-600/30" aria-current="page">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="{{ $pageClasses }} text-zinc-600 hover:bg-brand-50 hover:text-brand-700" aria-label="Страница {{ $page }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="{{ $arrowClasses }}" aria-label="Следующая страница">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </a>
            @else
                <span class="{{ $disabledArrowClasses }}" aria-disabled="true" aria-label="Следующая страница">
                    <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </span>
            @endif
        </div>
    </nav>
@endif
