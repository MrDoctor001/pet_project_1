@props(['product'])

<article class="group flex flex-col overflow-hidden rounded-2xl border border-gray-200 bg-white transition duration-200 hover:-translate-y-0.5 hover:border-gray-300 hover:shadow-lg hover:shadow-gray-200/70">
    <div class="relative aspect-square overflow-hidden bg-linear-to-br from-indigo-50 via-white to-gray-100">
        @if ($product->image)
            <img
                src="{{ Storage::url($product->image) }}"
                alt="{{ $product->name }}"
                class="size-full object-cover transition duration-300 group-hover:scale-105"
            >
        @else
            <div class="flex size-full items-center justify-center text-indigo-200 transition duration-300 group-hover:scale-105">
                <svg class="size-20" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909M3.75 21h16.5A2.25 2.25 0 0 0 22.5 18.75V5.25A2.25 2.25 0 0 0 20.25 3H3.75A2.25 2.25 0 0 0 1.5 5.25v13.5A2.25 2.25 0 0 0 3.75 21Zm10.5-11.25h.008v.008h-.008V9.75Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                </svg>
            </div>
        @endif

        @if ($product->stock === 0)
            <span class="absolute top-3 left-3 rounded-full bg-gray-900/80 px-2.5 py-1 text-xs font-medium text-white backdrop-blur">
                Нет в наличии
            </span>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-1.5 p-4">
        <p class="text-xs font-medium tracking-wide text-indigo-600 uppercase">
            {{ $product->category->name }}
        </p>

        <h3 class="line-clamp-2 font-medium text-gray-900">
            {{ $product->name }}
        </h3>

        <div class="mt-auto flex items-end justify-between gap-2 pt-3">
            <x-price :amount="$product->price" class="text-lg font-semibold text-gray-900" />

            @if ($product->stock > 0)
                <span class="text-xs font-medium text-emerald-600">В наличии</span>
            @endif
        </div>
    </div>
</article>
