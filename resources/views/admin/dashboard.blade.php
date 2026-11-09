<x-layouts.admin title="Главная">
    <p class="-mt-3 text-zinc-500">Здравствуйте, {{ auth()->user()->name }}! Вот что происходит в магазине.</p>

    <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        @foreach ([
            ['label' => 'Всего заказов', 'value' => $ordersCount, 'hint' => 'за всё время', 'route' => route('admin.orders.index'), 'accent' => 'text-zinc-900'],
            ['label' => 'Новые заказы', 'value' => $newOrdersCount, 'hint' => 'ждут обработки', 'route' => route('admin.orders.index', ['status' => 'new']), 'accent' => $newOrdersCount > 0 ? 'text-brand-600' : 'text-zinc-900'],
            ['label' => 'Выручка', 'value' => null, 'hint' => 'без отменённых заказов', 'route' => route('admin.orders.index'), 'accent' => 'text-emerald-600'],
            ['label' => 'Нет в наличии', 'value' => $outOfStockCount, 'hint' => trans_choice('товар закончился|товара закончились|товаров закончились', $outOfStockCount), 'route' => route('admin.products.index'), 'accent' => $outOfStockCount > 0 ? 'text-red-600' : 'text-zinc-900'],
        ] as $card)
            <a
                href="{{ $card['route'] }}"
                class="flex flex-col gap-1 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 transition hover:shadow-md hover:ring-brand-200"
            >
                <span class="text-sm font-semibold text-zinc-500">{{ $card['label'] }}</span>
                <span class="text-3xl font-extrabold tracking-tight {{ $card['accent'] }}">
                    @if ($card['value'] === null)
                        <x-price :amount="$revenue" />
                    @else
                        {{ $card['value'] }}
                    @endif
                </span>
                <span class="text-xs text-zinc-400">{{ $card['hint'] }}</span>
            </a>
        @endforeach
    </div>

    <section class="rounded-3xl bg-white shadow-sm ring-1 ring-zinc-900/5">
        <div class="flex items-center justify-between gap-4 px-4 pt-5 pb-3 sm:px-6">
            <h2 class="text-lg font-bold">Последние заказы</h2>
            <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-brand-600 transition hover:text-brand-500">Все заказы →</a>
        </div>

        @if ($latestOrders->isEmpty())
            <p class="px-6 pt-4 pb-10 text-center text-sm text-zinc-500">Заказов пока нет</p>
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($latestOrders as $order)
                    <li>
                        <a href="{{ route('admin.orders.show', $order) }}" class="flex flex-wrap items-center gap-x-5 gap-y-2 px-4 py-3.5 transition hover:bg-zinc-50 sm:px-6">
                            <div class="flex min-w-0 flex-1 basis-48 flex-col">
                                <span class="truncate font-semibold text-zinc-900">
                                    №{{ $order->id }}
                                    <span class="font-medium text-zinc-500">· {{ $order->customer_name }}</span>
                                </span>
                                <time datetime="{{ $order->created_at->toIso8601String() }}" class="text-sm text-zinc-500">
                                    {{ $order->created_at->format('d.m.Y H:i') }}
                                </time>
                            </div>

                            <div class="flex items-center gap-5 text-sm">
                                <x-price :amount="$order->total" class="w-28 text-right font-bold" />
                                <x-order-status :status="$order->status" class="w-24 text-center" />
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
</x-layouts.admin>
