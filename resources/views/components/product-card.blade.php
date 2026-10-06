@props(['product'])

<article class="group relative flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white transition duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-lg hover:shadow-gray-200/70 has-[a:focus-visible]:ring-2 has-[a:focus-visible]:ring-indigo-500">
    <x-product-image :product="$product" class="aspect-square">
        @if ($product->stock === 0)
            <span class="absolute top-3 left-3 rounded-full bg-gray-900/80 px-2.5 py-1 text-xs font-medium text-white backdrop-blur">
                Нет в наличии
            </span>
        @endif
    </x-product-image>

    <div class="flex flex-1 flex-col gap-1.5 p-4">
        <p class="text-xs font-medium tracking-wide text-indigo-600 uppercase">
            {{ $product->category->name }}
        </p>

        <h3 class="line-clamp-2 font-medium text-gray-900">
            {{-- after:absolute растягивает ссылку на всю карточку --}}
            <a href="{{ route('products.show', $product) }}" class="after:absolute after:inset-0 focus:outline-none">
                {{ $product->name }}
            </a>
        </h3>

        <div class="mt-auto flex items-end justify-between gap-2 pt-3">
            <x-price :amount="$product->price" class="text-lg font-semibold text-gray-900" />

            @if ($product->stock > 0)
                <span class="text-xs font-medium text-emerald-600">В наличии</span>
            @endif
        </div>
    </div>
</article>
