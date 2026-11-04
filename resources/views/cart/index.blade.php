<x-layouts.app title="Корзина">
    <div class="flex flex-col gap-8">
        <h1 class="text-3xl font-semibold tracking-tight">Корзина</h1>

        @if ($errors->any())
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $errors->first() }}
            </div>
        @endif

        @if ($products->isEmpty())
            <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-zinc-300 bg-white px-6 py-16 text-center">
                <svg class="size-12 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                </svg>
                <p class="font-medium text-zinc-900">В корзине пока пусто</p>
                <p class="text-sm text-zinc-500">Загляните в каталог — там много интересного.</p>
                <a
                    href="{{ route('catalog.index') }}"
                    class="mt-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500"
                >
                    Перейти в каталог
                </a>
            </div>
        @else
            <div class="grid gap-8 lg:grid-cols-3 lg:items-start">
                <ul class="divide-y divide-zinc-200 rounded-2xl border border-zinc-200 bg-white lg:col-span-2">
                    @foreach ($products as $product)
                        @php($quantity = $cart[$product->id])

                        <li class="flex gap-4 p-4 sm:gap-6 sm:p-6">
                            <a href="{{ route('products.show', $product) }}" class="shrink-0">
                                <x-product-image :product="$product" class="size-20 rounded-xl sm:size-24" />
                            </a>

                            <div class="flex flex-1 flex-col gap-3">
                                <div class="flex justify-between gap-4">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-xs font-medium tracking-wide text-brand-600 uppercase">
                                            {{ $product->category->name }}
                                        </p>
                                        <a href="{{ route('products.show', $product) }}" class="font-medium text-zinc-900 transition hover:text-brand-600">
                                            {{ $product->name }}
                                        </a>
                                        <p class="text-sm text-zinc-500">
                                            <x-price :amount="$product->price" /> за шт.
                                        </p>
                                    </div>

                                    <x-price :amount="$product->price * $quantity" class="font-semibold text-zinc-900" />
                                </div>

                                <div class="flex flex-wrap items-center gap-4">
                                    <form method="POST" action="{{ route('cart.update', $product) }}" class="flex items-center gap-2">
                                        @csrf
                                        @method('PATCH')

                                        <label for="quantity-{{ $product->id }}" class="text-sm text-zinc-500">Кол-во</label>
                                        <input
                                            id="quantity-{{ $product->id }}"
                                            name="quantity"
                                            type="number"
                                            required
                                            min="1"
                                            max="{{ $product->stock }}"
                                            value="{{ $quantity }}"
                                            data-auto-submit
                                            class="w-20 rounded-lg border border-zinc-300 bg-white px-3 py-1.5 text-center text-sm transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none"
                                        >
                                    </form>

                                    <form method="POST" action="{{ route('cart.destroy', $product) }}">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit" class="text-sm font-medium text-zinc-500 transition hover:text-red-600">
                                            Удалить
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>

                <aside class="flex flex-col gap-5 rounded-2xl border border-zinc-200 bg-white p-6 lg:sticky lg:top-24">
                    <h2 class="text-lg font-semibold">Ваш заказ</h2>

                    <dl class="flex flex-col gap-3 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-zinc-500">Товаров</dt>
                            <dd class="font-medium">{{ $products->sum(fn ($product) => $cart[$product->id]) }} шт.</dd>
                        </div>
                        <div class="flex justify-between border-t border-zinc-200 pt-3 text-base font-semibold">
                            <dt>К оплате</dt>
                            <dd><x-price :amount="$total" /></dd>
                        </div>
                    </dl>

                    <a
                        href="{{ route('checkout.create') }}"
                        class="rounded-xl bg-brand-600 px-6 py-3 text-center text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
                    >
                        @auth
                            Оформить заказ
                        @else
                            Войти и оформить заказ
                        @endauth
                    </a>

                    @guest
                        <p class="text-center text-xs text-zinc-500">
                            Заказ могут оформить только зарегистрированные покупатели.
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="font-medium text-brand-600 transition hover:text-brand-500">Создать аккаунт</a>
                            @endif
                        </p>
                    @endguest

                    <a href="{{ route('catalog.index') }}" class="text-center text-sm font-medium text-brand-600 transition hover:text-brand-500">
                        Продолжить покупки
                    </a>
                </aside>
            </div>
        @endif
    </div>
</x-layouts.app>
