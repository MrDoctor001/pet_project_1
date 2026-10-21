@props(['product'])

<article class="group relative flex flex-col overflow-hidden rounded-3xl bg-white shadow-sm shadow-zinc-900/5 ring-1 ring-zinc-900/5 transition duration-200 hover:-translate-y-1 hover:shadow-xl hover:shadow-zinc-900/10 has-[a:focus-visible]:ring-2 has-[a:focus-visible]:ring-brand-500">
    <x-product-image :product="$product" class="aspect-square">
        @if ($product->stock === 0)
            <span class="absolute top-3 left-3 rounded-full bg-ink-900/85 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                Нет в наличии
            </span>
        @endif
    </x-product-image>

    <div class="flex flex-1 flex-col gap-1.5 p-5">
        <p class="text-xs font-bold tracking-wider text-brand-600 uppercase">
            {{ $product->category->name }}
        </p>

        <h3 class="line-clamp-2 font-semibold text-zinc-900">
            {{-- after:absolute растягивает ссылку на всю карточку --}}
            <a href="{{ route('products.show', $product) }}" class="after:absolute after:inset-0 focus:outline-none">
                {{ $product->name }}
            </a>
        </h3>

        <div class="mt-auto flex items-end justify-between gap-2 pt-3">
            <x-price :amount="$product->price" class="text-xl font-extrabold text-zinc-900" />

            @if ($product->stock > 0)
                <span class="rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700">В наличии</span>
            @endif
        </div>
    </div>
</article>
