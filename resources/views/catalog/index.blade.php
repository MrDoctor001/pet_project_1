{{-- Баннер и плитки показываем только на первой странице общего каталога --}}
@php
    $currentCategory ??= null;
    $isHomePage = $currentCategory === null && $products->onFirstPage();
    $categoriesWithCover = $categories->filter(fn ($category) => $category->coverProduct !== null);
@endphp

<x-layouts.app :title="$currentCategory?->name ?? 'Каталог'">
    @if ($isHomePage)
        <x-slot:hero>
            <section class="relative isolate mx-3 mt-3 overflow-hidden rounded-[2rem] bg-ink-950 text-white sm:mx-4 sm:mt-4">
                {{-- Фиолетовое свечение на фоне баннера --}}
                <div class="absolute -top-32 -left-24 -z-10 size-[28rem] rounded-full bg-brand-500/25 blur-3xl" aria-hidden="true"></div>
                <div class="absolute -right-24 -bottom-40 -z-10 size-[28rem] rounded-full bg-brand-700/35 blur-3xl" aria-hidden="true"></div>

                <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:px-8 lg:py-20">
                    <div class="flex flex-col items-start gap-6">
                        <span class="rounded-full border border-white/15 bg-white/5 px-4 py-1.5 text-xs font-semibold tracking-wide text-brand-200 uppercase">
                            Доставка по всему Казахстану
                        </span>

                        <h1 class="text-4xl leading-tight font-extrabold tracking-tight sm:text-5xl">
                            Всё нужное —<br>
                            <span class="text-brand-400">в одном месте</span>
                        </h1>

                        <p class="max-w-md text-lg leading-relaxed text-zinc-300">
                            Техника, одежда, книги и товары для дома. Оформите заказ за минуту и оплатите при получении.
                        </p>

                        <div class="flex flex-wrap gap-3">
                            <a
                                href="#catalog"
                                class="rounded-full bg-brand-500 px-7 py-3.5 text-sm font-bold text-white shadow-lg shadow-brand-500/30 transition hover:bg-brand-400"
                            >
                                Смотреть каталог
                            </a>
                            <a
                                href="{{ route('cart.index') }}"
                                class="rounded-full border border-white/20 px-7 py-3.5 text-sm font-bold text-white transition hover:bg-white/10"
                            >
                                Корзина
                            </a>
                        </div>

                        <dl class="mt-2 flex flex-wrap gap-x-10 gap-y-4">
                            <div>
                                <dt class="text-xs text-zinc-400">Товаров</dt>
                                <dd class="text-2xl font-extrabold">{{ $products->total() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-zinc-400">Категорий</dt>
                                <dd class="text-2xl font-extrabold">{{ $categories->count() }}</dd>
                            </div>
                            <div>
                                <dt class="text-xs text-zinc-400">Оплата</dt>
                                <dd class="text-2xl font-extrabold">при получении</dd>
                            </div>
                        </dl>
                    </div>

                    @if ($categoriesWithCover->count() >= 3)
                        @php($heroCovers = $categoriesWithCover->take(3)->values())

                        <div class="hidden h-[26rem] grid-cols-2 grid-rows-2 gap-4 lg:grid" aria-hidden="true">
                            <x-product-image :product="$heroCovers[0]->coverProduct" class="row-span-2 rounded-3xl shadow-2xl shadow-black/40" />
                            <x-product-image :product="$heroCovers[1]->coverProduct" class="rounded-3xl shadow-2xl shadow-black/40" />
                            <x-product-image :product="$heroCovers[2]->coverProduct" class="rounded-3xl shadow-2xl shadow-black/40" />
                        </div>
                    @endif
                </div>
            </section>
        </x-slot:hero>
    @endif

    <div class="flex flex-col gap-12">
        @if ($isHomePage)
            @if ($categoriesWithCover->isNotEmpty())
                <section class="flex flex-col gap-6">
                    <h2 class="text-2xl font-extrabold tracking-tight">Категории</h2>

                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-6">
                        @foreach ($categoriesWithCover as $category)
                            <a
                                href="{{ route('catalog.category', $category) }}"
                                class="group relative flex aspect-[4/5] flex-col justify-end overflow-hidden rounded-3xl"
                            >
                                <div class="absolute inset-0">
                                    <x-product-image :product="$category->coverProduct" class="size-full" />
                                </div>
                                {{-- Затемнение снизу, чтобы белый текст читался на светлом фото --}}
                                <div class="absolute inset-0 bg-linear-to-t from-ink-950/85 via-ink-950/10 to-transparent" aria-hidden="true"></div>

                                <div class="relative flex flex-col p-4 text-white">
                                    <span class="font-bold">{{ $category->name }}</span>
                                    <span class="text-xs text-zinc-300">Товаров: {{ $category->products_count }}</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        @endif

        <section id="catalog" class="flex scroll-mt-24 flex-col gap-8">
            <div class="flex flex-col gap-1">
                @if ($isHomePage)
                    <h2 class="text-2xl font-extrabold tracking-tight">Все товары</h2>
                @else
                    <h1 class="text-3xl font-extrabold tracking-tight sm:text-4xl">{{ $currentCategory?->name ?? 'Каталог' }}</h1>
                @endif
                <p class="text-zinc-500">Найдено товаров: {{ $products->total() }}</p>
            </div>

            <x-category-nav :categories="$categories" :current="$currentCategory" />

            @if ($products->isEmpty())
                <div class="flex flex-col items-center gap-3 rounded-3xl border border-dashed border-zinc-300 bg-white/60 px-6 py-16 text-center">
                    <svg class="size-12 text-zinc-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z" />
                    </svg>
                    <p class="font-semibold text-zinc-900">
                        {{ $currentCategory ? 'В этой категории пока нет товаров' : 'Товаров пока нет' }}
                    </p>
                    <p class="text-sm text-zinc-500">Загляните позже — мы скоро всё добавим.</p>
                </div>
            @else
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    @foreach ($products as $product)
                        <x-product-card :product="$product" />
                    @endforeach
                </div>

                {{ $products->links() }}
            @endif
        </section>
    </div>
</x-layouts.app>
