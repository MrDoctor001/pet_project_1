<x-layouts.admin title="Товары">
    <x-slot:actions>
        <a
            href="{{ route('admin.products.create') }}"
            class="flex items-center gap-2 rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500"
        >
            <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
            </svg>
            Добавить товар
        </a>
    </x-slot:actions>

    <form method="GET" action="{{ route('admin.products.index') }}" class="flex gap-3">
        <input
            type="search"
            name="search"
            value="{{ request('search') }}"
            placeholder="Поиск по названию"
            aria-label="Поиск по названию"
            class="w-full max-w-md rounded-xl border border-zinc-300 bg-white px-4 py-2.5 text-sm transition focus:border-brand-500 focus:ring-4 focus:ring-brand-500/15 focus:outline-none"
        >
        <button type="submit" class="rounded-xl bg-ink-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-ink-700">
            Найти
        </button>
    </form>

    <section class="rounded-3xl bg-white shadow-sm ring-1 ring-zinc-900/5">
        @if ($products->isEmpty())
            <div class="flex flex-col items-center gap-2 px-6 py-14 text-center">
                <p class="font-semibold">Ничего не найдено</p>
                <p class="text-sm text-zinc-500">
                    @if (request('search'))
                        Попробуйте изменить запрос или <a href="{{ route('admin.products.index') }}" class="font-semibold text-brand-600 hover:text-brand-500">сбросить поиск</a>
                    @else
                        Добавьте первый товар
                    @endif
                </p>
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($products as $product)
                    <li class="flex flex-wrap items-center gap-x-5 gap-y-3 px-4 py-4 sm:px-6">
                        <x-product-image :product="$product" class="size-14 shrink-0 rounded-xl" />

                        <div class="flex min-w-0 flex-1 basis-48 flex-col">
                            <a href="{{ route('admin.products.edit', $product) }}" class="truncate font-semibold text-zinc-900 hover:text-brand-600">
                                {{ $product->name }}
                            </a>
                            <span class="text-sm text-zinc-500">{{ $product->category->name }}</span>
                        </div>

                        <div class="flex items-center gap-5 text-sm">
                            <x-price :amount="$product->price" class="w-24 text-right font-bold" />

                            <span @class([
                                'w-20 text-right font-medium',
                                'text-red-600' => $product->stock === 0,
                                'text-amber-600' => $product->stock > 0 && $product->stock < 5,
                                'text-zinc-500' => $product->stock >= 5,
                            ])>
                                {{ $product->stock }} шт.
                            </span>

                            <span @class([
                                'w-20 rounded-full px-2.5 py-1 text-center text-xs font-semibold',
                                'bg-emerald-50 text-emerald-700' => $product->is_active,
                                'bg-zinc-100 text-zinc-500' => ! $product->is_active,
                            ])>
                                {{ $product->is_active ? 'В продаже' : 'Скрыт' }}
                            </span>

                            <a
                                href="{{ route('admin.products.edit', $product) }}"
                                aria-label="Изменить {{ $product->name }}"
                                class="flex size-9 items-center justify-center rounded-lg text-zinc-400 transition hover:bg-brand-50 hover:text-brand-600"
                            >
                                <svg class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                </svg>
                            </a>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>

    {{ $products->links() }}
</x-layouts.admin>
