<x-layouts.admin :title="'Заказ №'.$order->id">
    <x-slot:actions>
        <a href="{{ route('admin.orders.index') }}" class="text-sm font-semibold text-zinc-500 transition hover:text-zinc-900">← Все заказы</a>
    </x-slot:actions>

    <div class="grid gap-6 lg:grid-cols-3 lg:items-start">
        <div class="flex flex-col gap-6 lg:col-span-2">
            <section class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5">
                <div class="flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-lg font-bold">Состав заказа</h2>
                    <time datetime="{{ $order->created_at->toIso8601String() }}" class="text-sm text-zinc-500">
                        {{ $order->created_at->format('d.m.Y H:i') }}
                    </time>
                </div>

                <ul class="divide-y divide-zinc-100">
                    @foreach ($order->items as $item)
                        <li class="flex justify-between gap-4 py-3 first:pt-0">
                            <div class="flex flex-col">
                                <span class="font-medium text-zinc-900">{{ $item->product_name }}</span>
                                <span class="text-sm text-zinc-500">
                                    {{ $item->quantity }} × <x-price :amount="$item->price" />
                                    @if ($item->product_id === null)
                                        · <span class="text-zinc-400">товар удалён</span>
                                    @endif
                                </span>
                            </div>
                            <x-price :amount="$item->price * $item->quantity" class="font-medium" />
                        </li>
                    @endforeach
                </ul>

                <div class="flex justify-between border-t border-zinc-100 pt-4 text-base font-bold">
                    <span>Итого</span>
                    <x-price :amount="$order->total" />
                </div>
            </section>

            <section class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5">
                <h2 class="text-lg font-bold">Покупатель</h2>

                <dl class="grid gap-x-6 gap-y-3 text-sm sm:grid-cols-[max-content_1fr]">
                    <dt class="text-zinc-500">Имя</dt>
                    <dd class="text-zinc-900">{{ $order->customer_name }}</dd>

                    <dt class="text-zinc-500">Телефон</dt>
                    <dd><a href="tel:{{ $order->phone }}" class="text-brand-600 hover:text-brand-500">{{ $order->phone }}</a></dd>

                    @if ($order->email)
                        <dt class="text-zinc-500">Email</dt>
                        <dd><a href="mailto:{{ $order->email }}" class="text-brand-600 hover:text-brand-500">{{ $order->email }}</a></dd>
                    @endif

                    <dt class="text-zinc-500">Адрес</dt>
                    <dd class="text-zinc-900">{{ $order->address }}</dd>

                    @if ($order->comment)
                        <dt class="text-zinc-500">Комментарий</dt>
                        <dd class="whitespace-pre-line text-zinc-900">{{ $order->comment }}</dd>
                    @endif

                    <dt class="text-zinc-500">Аккаунт</dt>
                    <dd class="text-zinc-900">{{ $order->user ? $order->user->name.' ('.$order->user->email.')' : 'Без аккаунта' }}</dd>
                </dl>
            </section>
        </div>

        <section class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 lg:sticky lg:top-10">
            <div class="flex items-center justify-between gap-3">
                <h2 class="text-lg font-bold">Статус</h2>
                <x-order-status :status="$order->status" />
            </div>

            @if (Route::has('admin.orders.update'))
                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="flex flex-col gap-3">
                    @csrf
                    @method('PATCH')

                    <label for="status" class="sr-only">Новый статус</label>
                    <select
                        id="status"
                        name="status"
                        @error('status') aria-invalid="true" aria-describedby="status-error" @enderror
                        @class([
                            'rounded-xl border bg-white px-4 py-2.5 text-sm transition focus:ring-4 focus:outline-none',
                            'border-zinc-300 focus:border-brand-500 focus:ring-brand-500/15' => ! $errors->has('status'),
                            'border-red-400 focus:border-red-500 focus:ring-red-500/15' => $errors->has('status'),
                        ])
                    >
                        @foreach (\App\Enums\OrderStatus::cases() as $status)
                            <option value="{{ $status->value }}" @selected(old('status', $order->status->value) === $status->value)>
                                {{ $status->label() }}
                            </option>
                        @endforeach
                    </select>

                    @error('status')
                        <p id="status-error" class="text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500">
                        Сохранить статус
                    </button>
                </form>
            @endif
        </section>
    </div>
</x-layouts.admin>
