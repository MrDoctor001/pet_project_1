<x-layouts.admin title="Главная">
    <p class="-mt-3 text-zinc-500">Здравствуйте, {{ auth()->user()->name }}! Выберите раздел, чтобы начать работу.</p>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
        @foreach ([
            ['route' => 'admin.products.index', 'label' => 'Товары', 'text' => 'Добавляйте и редактируйте товары, меняйте цены и остатки'],
            ['route' => 'admin.orders.index', 'label' => 'Заказы', 'text' => 'Смотрите заказы покупателей и меняйте их статус'],
            ['route' => 'admin.categories.index', 'label' => 'Категории', 'text' => 'Управляйте разделами каталога'],
        ] as $section)
            @if (Route::has($section['route']))
                <a
                    href="{{ route($section['route']) }}"
                    class="group flex flex-col gap-2 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 transition hover:shadow-md hover:ring-brand-200"
                >
                    <span class="flex items-center justify-between text-lg font-bold">
                        {{ $section['label'] }}
                        <svg class="size-5 text-zinc-300 transition group-hover:translate-x-0.5 group-hover:text-brand-500" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                        </svg>
                    </span>
                    <span class="text-sm text-zinc-500">{{ $section['text'] }}</span>
                </a>
            @else
                <div class="flex flex-col gap-2 rounded-3xl border border-dashed border-zinc-300 p-6">
                    <span class="text-lg font-bold text-zinc-400">{{ $section['label'] }}</span>
                    <span class="text-sm text-zinc-400">Скоро</span>
                </div>
            @endif
        @endforeach
    </div>
</x-layouts.admin>
