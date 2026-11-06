<x-layouts.app title="Профиль">
    <div class="flex flex-col gap-8">
        <div class="flex items-center gap-4">
            <span class="flex size-14 shrink-0 items-center justify-center rounded-2xl bg-brand-600 text-xl font-extrabold text-white shadow-lg shadow-brand-600/30">
                {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
            </span>
            <div class="flex min-w-0 flex-col">
                <h1 class="truncate text-2xl font-extrabold tracking-tight sm:text-3xl">{{ $user->name }}</h1>
                <p class="truncate text-zinc-500">{{ $user->email }}</p>
            </div>
        </div>

        <div class="grid gap-6 lg:grid-cols-2">
            <section class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8">
                <div class="flex flex-col gap-1">
                    <h2 class="text-lg font-bold">Личные данные</h2>
                    <p class="text-sm text-zinc-500">Имя и email, на который приходят письма</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="flex flex-col gap-5">
                    @csrf
                    @method('PATCH')

                    <x-form-field name="name" label="Имя" :value="$user->name" autocomplete="name" required />
                    <x-form-field name="email" label="Email" type="email" :value="$user->email" autocomplete="email" required />

                    <button
                        type="submit"
                        class="self-start rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
                    >
                        Сохранить
                    </button>
                </form>
            </section>

            <section class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8">
                <div class="flex flex-col gap-1">
                    <h2 class="text-lg font-bold">Смена пароля</h2>
                    <p class="text-sm text-zinc-500">Новый пароль — не короче 8 символов</p>
                </div>

                <form method="POST" action="{{ route('profile.password') }}" class="flex flex-col gap-5">
                    @csrf
                    @method('PUT')

                    <x-form-field name="current_password" label="Текущий пароль" type="password" autocomplete="current-password" required />
                    <x-form-field name="password" label="Новый пароль" type="password" autocomplete="new-password" required />
                    <x-form-field name="password_confirmation" label="Повторите новый пароль" type="password" autocomplete="new-password" required />

                    <button
                        type="submit"
                        class="self-start rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
                    >
                        Сменить пароль
                    </button>
                </form>
            </section>
        </div>

        <section class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8">
            <h2 class="text-lg font-bold">Мои заказы</h2>

            @if ($orders->isEmpty())
                <div class="flex flex-col items-center gap-4 py-8 text-center">
                    <p class="text-zinc-500">Вы ещё ничего не заказывали</p>
                    <a
                        href="{{ route('catalog.index') }}"
                        class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500"
                    >
                        Перейти в каталог
                    </a>
                </div>
            @else
                <ul class="-mx-3 flex flex-col divide-y divide-zinc-100">
                    @foreach ($orders as $order)
                        <li>
                            <a
                                href="{{ route('orders.show', $order) }}"
                                class="flex flex-wrap items-center gap-x-4 gap-y-2 rounded-2xl px-3 py-4 transition hover:bg-zinc-50"
                            >
                                <div class="flex min-w-0 flex-1 flex-col">
                                    <span class="font-semibold text-zinc-900">Заказ №{{ $order->id }}</span>
                                    <span class="text-sm text-zinc-500">
                                        <time datetime="{{ $order->created_at->toIso8601String() }}">{{ $order->created_at->format('d.m.Y') }}</time>
                                        · {{ trans_choice(':count товар|:count товара|:count товаров', $order->items_count) }}
                                    </span>
                                </div>

                                <x-order-status :status="$order->status" />

                                <x-price :amount="$order->total" class="w-28 text-right font-bold" />

                                <svg class="size-5 shrink-0 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                </svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif
        </section>
    </div>
</x-layouts.app>
