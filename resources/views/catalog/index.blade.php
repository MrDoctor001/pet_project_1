<x-layouts.app title="Каталог">
    <div class="flex flex-col gap-8">
        <div class="flex flex-col gap-1">
            <h1 class="text-3xl font-semibold tracking-tight">Каталог</h1>
            <p class="text-gray-500">Найдено товаров: {{ $products->total() }}</p>
        </div>

        @if ($products->isEmpty())
            <div class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-gray-300 bg-white px-6 py-16 text-center">
                <svg class="size-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                </svg>
                <p class="font-medium text-gray-900">Товаров пока нет</p>
                <p class="text-sm text-gray-500">Загляните позже — мы скоро всё добавим.</p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach ($products as $product)
                    <x-product-card :product="$product" />
                @endforeach
            </div>

            {{ $products->links() }}
        @endif
    </div>
</x-layouts.app>
