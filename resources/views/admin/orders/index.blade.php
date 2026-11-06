<x-layouts.admin title="Заказы">
    <div class="flex flex-col gap-4">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="flex gap-3">
            @if (request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
            @endif

            <input
                type="search"
                name="search"
                value="{{ request('search') }}"
                placeholder="Имя или телефон покупателя"
                aria-label="Имя или телефон покупателя"
                class="w-full max-w-md rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none"
            >
            <button type="submit" class="rounded-xl bg-ink-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-ink-700">
                Найти
            </button>
        </form>

        <nav aria-label="Фильтр по статусу" class="-mx-4 overflow-x-auto px-4 sm:mx-0 sm:px-0">
            <ul class="flex gap-2 text-sm font-semibold whitespace-nowrap">
                @foreach ([null => 'Все', ...collect(\App\Enums\OrderStatus::cases())->mapWithKeys(fn ($status) => [$status->value => $status->label()])] as $value => $label)
                    <li>
                        <a
                            href="{{ route('admin.orders.index', array_filter(['status' => $value, 'search' => request('search')])) }}"
                            @if (request('status', '') === (string) $value) aria-current="page" @endif
                            @class([
                                'block rounded-full px-4 py-2 transition',
                                'bg-ink-900 text-white' => request('status', '') === (string) $value,
                                'bg-white text-zinc-600 ring-1 ring-zinc-900/5 hover:text-zinc-900' => request('status', '') !== (string) $value,
                            ])
                        >
                            {{ $label }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </nav>
    </div>

    <section class="rounded-3xl bg-white shadow-sm ring-1 ring-zinc-900/5">
        @if ($orders->isEmpty())
            <div class="flex flex-col items-center gap-2 px-6 py-14 text-center">
                <p class="font-semibold">Заказов не найдено</p>
                @if (request('search') || request('status'))
                    <p class="text-sm text-zinc-500">
                        Попробуйте изменить условия или <a href="{{ route('admin.orders.index') }}" class="font-semibold text-brand-600 hover:text-brand-500">сбросить фильтры</a>
                    </p>
                @endif
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($orders as $order)
                    <li>
                        <a href="{{ route('admin.orders.show', $order) }}" class="flex flex-wrap items-center gap-x-5 gap-y-3 px-4 py-4 transition hover:bg-zinc-50 sm:px-6">
                            <div class="flex min-w-0 flex-1 basis-56 flex-col">
                                <span class="font-semibold text-zinc-900">
                                    №{{ $order->id }}
                                    <span class="font-medium text-zinc-500">· {{ $order->customer_name }}</span>
                                </span>
                                <span class="text-sm text-zinc-500">
                                    {{ $order->phone }} ·
                                    <time datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('d.m.Y H:i') }}</time>
                                </span>
                            </div>

                            <div class="flex items-center gap-5 text-sm">
                                <x-price :amount="$order->total" class="w-28 text-right font-bold" />
                                <x-order-status :status="$order->status" class="w-24 text-center" />
                                <svg class="size-5 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{ $orders->links() }}
</x-layouts.admin>
