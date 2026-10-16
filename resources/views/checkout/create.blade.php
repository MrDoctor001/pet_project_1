<x-layouts.app title="Оформление заказа">
    <div class="flex flex-col gap-8">
        <nav aria-label="Навигационная цепочка" class="text-sm text-gray-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li>
                    <a href="{{ route('cart.index') }}" class="transition hover:text-gray-900">Корзина</a>
                </li>
                <li aria-hidden="true">/</li>
                <li class="text-gray-900" aria-current="page">Оформление заказа</li>
            </ol>
        </nav>

        <h1 class="text-3xl font-semibold tracking-tight">Оформление заказа</h1>

        @error('cart')
            <div role="alert" class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ $message }}
                <a href="{{ route('cart.index') }}" class="font-semibold underline">Изменить корзину</a>
            </div>
        @enderror

        <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-8 lg:grid-cols-3 lg:items-start">
            @csrf

            <div class="flex flex-col gap-6 lg:col-span-2">
                <section class="flex flex-col gap-5 rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-lg font-semibold">Контактные данные</h2>

                    <x-form-field name="customer_name" label="Имя и фамилия" autocomplete="name" required />

                    <div class="grid gap-5 sm:grid-cols-2">
                        <x-form-field name="phone" label="Телефон" type="tel" autocomplete="tel" placeholder="+7 700 000 00 00" required />
                        <x-form-field name="email" label="Email" type="email" autocomplete="email" />
                    </div>
                </section>

                <section class="flex flex-col gap-5 rounded-2xl border border-gray-200 bg-white p-6">
                    <h2 class="text-lg font-semibold">Доставка</h2>

                    <x-form-field name="address" label="Адрес" autocomplete="street-address" placeholder="Город, улица, дом, квартира" required />

                    <div class="flex flex-col gap-1.5">
                        <label for="comment" class="text-sm font-medium text-gray-900">
                            Комментарий к заказу
                            <span class="font-normal text-gray-400">(необязательно)</span>
                        </label>
                        <textarea
                            id="comment"
                            name="comment"
                            rows="3"
                            placeholder="Например: позвоните за час до доставки"
                            @error('comment') aria-invalid="true" aria-describedby="comment-error" @enderror
                            @class([
                                'rounded-xl border bg-white px-4 py-3 text-sm transition focus:ring-4 focus:outline-none',
                                'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500/15' => ! $errors->has('comment'),
                                'border-red-400 focus:border-red-500 focus:ring-red-500/15' => $errors->has('comment'),
                            ])
                        >{{ old('comment') }}</textarea>

                        @error('comment')
                            <p id="comment-error" class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </section>
            </div>

            <aside class="flex flex-col gap-5 rounded-2xl border border-gray-200 bg-white p-6 lg:sticky lg:top-24">
                <h2 class="text-lg font-semibold">Ваш заказ</h2>

                <ul class="flex flex-col gap-4">
                    @foreach ($products as $product)
                        <li class="flex items-center gap-3">
                            <x-product-image :product="$product" class="size-12 shrink-0 rounded-lg" />

                            <div class="flex min-w-0 flex-1 flex-col">
                                <span class="truncate text-sm font-medium text-gray-900">{{ $product->name }}</span>
                                <span class="text-xs text-gray-500">{{ $cart[$product->id] }} шт.</span>
                            </div>

                            <x-price :amount="$product->price * $cart[$product->id]" class="text-sm font-medium" />
                        </li>
                    @endforeach
                </ul>

                <div class="flex justify-between border-t border-gray-200 pt-4 text-base font-semibold">
                    <span>К оплате</span>
                    <x-price :amount="$total" />
                </div>

                <button
                    type="submit"
                    class="rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-indigo-600/30 transition hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                >
                    Подтвердить заказ
                </button>

                <p class="text-center text-xs text-gray-500">Оплата при получении</p>
            </aside>
        </form>
    </div>
</x-layouts.app>
