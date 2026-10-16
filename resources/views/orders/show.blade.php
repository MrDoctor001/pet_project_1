<x-layouts.app :title="'Заказ №'.$order->id">
    <div class="mx-auto flex w-full max-w-3xl flex-col gap-8">
        <div class="flex flex-col items-center gap-3 text-center">
            <div class="flex size-14 items-center justify-center rounded-full bg-emerald-100 text-emerald-600">
                <svg class="size-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                </svg>
            </div>
            <h1 class="text-3xl font-semibold tracking-tight">Заказ №{{ $order->id }} оформлен</h1>
            <p class="text-gray-500">Мы свяжемся с вами по телефону {{ $order->phone }}, чтобы подтвердить доставку.</p>
        </div>

        <section class="flex flex-col gap-5 rounded-2xl border border-gray-200 bg-white p-6">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-lg font-semibold">Состав заказа</h2>
                <div class="flex items-center gap-3 text-sm text-gray-500">
                    <time datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('d.m.Y H:i') }}</time>
                    <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium text-indigo-700">
                        {{ $order->status->label() }}
                    </span>
                </div>
            </div>

            <ul class="divide-y divide-gray-200">
                @foreach ($order->items as $item)
                    <li class="flex justify-between gap-4 py-3 first:pt-0">
                        <div class="flex flex-col">
                            <span class="font-medium text-gray-900">{{ $item->product_name }}</span>
                            <span class="text-sm text-gray-500">
                                {{ $item->quantity }} × <x-price :amount="$item->price" />
                            </span>
                        </div>
                        <x-price :amount="$item->price * $item->quantity" class="font-medium" />
                    </li>
                @endforeach
            </ul>

            <div class="flex justify-between border-t border-gray-200 pt-4 text-base font-semibold">
                <span>Итого</span>
                <x-price :amount="$order->total" />
            </div>
        </section>

        <section class="flex flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-6">
            <h2 class="text-lg font-semibold">Получатель</h2>

            <dl class="grid gap-x-6 gap-y-3 text-sm sm:grid-cols-[max-content_1fr]">
                <dt class="text-gray-500">Имя</dt>
                <dd class="text-gray-900">{{ $order->customer_name }}</dd>

                <dt class="text-gray-500">Телефон</dt>
                <dd class="text-gray-900">{{ $order->phone }}</dd>

                @if ($order->email)
                    <dt class="text-gray-500">Email</dt>
                    <dd class="text-gray-900">{{ $order->email }}</dd>
                @endif

                <dt class="text-gray-500">Адрес</dt>
                <dd class="text-gray-900">{{ $order->address }}</dd>

                @if ($order->comment)
                    <dt class="text-gray-500">Комментарий</dt>
                    <dd class="whitespace-pre-line text-gray-900">{{ $order->comment }}</dd>
                @endif
            </dl>
        </section>

        <a
            href="{{ route('catalog.index') }}"
            class="self-center rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-600/30 transition hover:bg-indigo-500"
        >
            Вернуться в каталог
        </a>
    </div>
</x-layouts.app>
