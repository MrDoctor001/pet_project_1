@php
    $sections = [
        [
            'route' => 'admin.dashboard',
            'active' => 'admin.dashboard',
            'label' => 'Главная',
            'icon' => 'm2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25',
        ],
        [
            'route' => 'admin.products.index',
            'active' => 'admin.products.*',
            'label' => 'Товары',
            'icon' => 'm21 7.5-9-5.25L3 7.5m18 0-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9',
        ],
        [
            'route' => 'admin.orders.index',
            'active' => 'admin.orders.*',
            'label' => 'Заказы',
            'icon' => 'M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z',
        ],
        [
            'route' => 'admin.categories.index',
            'active' => 'admin.categories.*',
            'label' => 'Категории',
            'icon' => 'M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3ZM6 6h.008v.008H6V6Z',
        ],
    ];
@endphp

<nav {{ $attributes->class('flex flex-col gap-1 text-sm font-semibold') }}>
    @foreach ($sections as $section)
        @if (Route::has($section['route']))
            <a
                href="{{ route($section['route']) }}"
                @class([
                    'flex items-center gap-3 rounded-xl px-3 py-2.5 transition',
                    'bg-brand-600 text-white shadow-lg shadow-brand-600/30' => request()->routeIs($section['active']),
                    'hover:bg-white/5 hover:text-white' => ! request()->routeIs($section['active']),
                ])
            >
                <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $section['icon'] }}" />
                </svg>
                {{ $section['label'] }}
            </a>
        @endif
    @endforeach

    <a href="{{ route('catalog.index') }}" class="mt-4 flex items-center gap-3 rounded-xl border-t border-white/10 px-3 pt-5 pb-2.5 transition hover:text-white">
        <svg class="size-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3" />
        </svg>
        На сайт
    </a>
</nav>
