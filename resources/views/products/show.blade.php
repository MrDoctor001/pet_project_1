<x-layouts.app :title="$product->name">
    <div class="flex flex-col gap-8">
        <nav aria-label="Навигационная цепочка" class="text-sm text-gray-500">
            <ol class="flex flex-wrap items-center gap-2">
                <li>
                    <a href="{{ route('catalog.index') }}" class="transition hover:text-gray-900">Каталог</a>
                </li>
                <li aria-hidden="true">/</li>
                <li>
                    <a href="{{ route('catalog.category', $product->category) }}" class="transition hover:text-gray-900">
                        {{ $product->category->name }}
                    </a>
                </li>
                <li aria-hidden="true">/</li>
                <li class="text-gray-900" aria-current="page">{{ $product->name }}</li>
            </ol>
        </nav>

        <div class="grid gap-8 lg:grid-cols-2 lg:gap-12">
            <x-product-image :product="$product" class="aspect-square rounded-3xl border border-gray-200" />

            <div class="flex flex-col gap-6">
                <div class="flex flex-col items-start gap-3">
                    <a
                        href="{{ route('catalog.category', $product->category) }}"
                        class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-medium tracking-wide text-indigo-700 uppercase transition hover:bg-indigo-100"
                    >
                        {{ $product->category->name }}
                    </a>

                    <h1 class="text-3xl font-semibold tracking-tight sm:text-4xl">{{ $product->name }}</h1>
                </div>

                <x-price :amount="$product->price" class="text-3xl font-semibold text-gray-900" />

                @if ($product->stock > 0)
                    <p class="flex items-center gap-2 text-sm font-medium text-emerald-700">
                        <span class="size-2 rounded-full bg-emerald-500"></span>
                        В наличии: {{ $product->stock }} шт.
                    </p>
                @else
                    <p class="flex items-center gap-2 text-sm font-medium text-gray-500">
                        <span class="size-2 rounded-full bg-gray-400"></span>
                        Нет в наличии
                    </p>
                @endif

                @if ($product->stock > 0)
                    <form method="POST" action="{{ route('cart.store', $product) }}" class="flex flex-col gap-2">
                        @csrf

                        <div class="flex flex-wrap items-center gap-3">
                            <label for="quantity" class="sr-only">Количество</label>
                            <input
                                id="quantity"
                                name="quantity"
                                type="number"
                                min="1"
                                max="{{ $product->stock }}"
                                value="{{ old('quantity', 1) }}"
                                class="w-24 rounded-xl border border-gray-300 bg-white px-4 py-3 text-center text-sm font-medium transition focus:border-indigo-500 focus:ring-4 focus:ring-indigo-500/15 focus:outline-none"
                            >

                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-semibold text-white shadow-sm shadow-indigo-600/30 transition hover:bg-indigo-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                                </svg>
                                В корзину
                            </button>
                        </div>

                        @error('quantity')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </form>
                @else
                    <button
                        type="button"
                        disabled
                        class="cursor-not-allowed rounded-xl bg-gray-200 px-6 py-3.5 text-sm font-semibold text-gray-500 sm:self-start"
                    >
                        Нет в наличии
                    </button>
                @endif

                @if ($product->description)
                    <div class="flex flex-col gap-2 border-t border-gray-200 pt-6">
                        <h2 class="text-sm font-semibold text-gray-900">Описание</h2>
                        <p class="leading-relaxed whitespace-pre-line text-gray-600">{{ $product->description }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
