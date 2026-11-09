<x-layouts.admin title="Категории">
    @if (Route::has('admin.categories.store'))
        <form method="POST" action="{{ route('admin.categories.store') }}" class="flex flex-col gap-1.5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5">
            @csrf

            <label for="name" class="text-sm font-semibold text-zinc-900">Новая категория</label>
            <div class="flex flex-wrap gap-3">
                <input
                    id="name"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Например: Спорт и отдых"
                    required
                    @error('name') aria-invalid="true" @enderror
                    @class([
                        'w-full max-w-md flex-1 rounded-xl border bg-white px-4 py-2.5 text-sm transition focus:ring-4 focus:outline-none',
                        'border-zinc-300 focus:border-brand-500 focus:ring-brand-500/15' => ! $errors->hasAny(['name', 'slug']),
                        'border-red-400 focus:border-red-500 focus:ring-red-500/15' => $errors->hasAny(['name', 'slug']),
                    ])
                >
                <button type="submit" class="rounded-xl bg-brand-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500">
                    Добавить
                </button>
            </div>

            @error('name')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('slug')
                <p class="text-sm text-red-600">Категория с таким названием уже есть</p>
            @enderror
        </form>
    @endif

    <section class="rounded-3xl bg-white shadow-sm ring-1 ring-zinc-900/5">
        @if ($categories->isEmpty())
            <div class="flex flex-col items-center gap-2 px-6 py-14 text-center">
                <p class="font-semibold">Категорий пока нет</p>
                <p class="text-sm text-zinc-500">Добавьте первую категорию, чтобы заводить товары</p>
            </div>
        @else
            <ul class="divide-y divide-zinc-100">
                @foreach ($categories as $category)
                    <li class="flex flex-wrap items-center gap-x-5 gap-y-2 px-4 py-4 sm:px-6">
                        <div class="flex min-w-0 flex-1 basis-48 flex-col">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="truncate font-semibold text-zinc-900 hover:text-brand-600">
                                {{ $category->name }}
                            </a>
                            <span class="truncate text-sm text-zinc-400">/categories/{{ $category->slug }}</span>
                        </div>

                        <div class="flex items-center gap-5 text-sm">
                            <span @class([
                                'w-24 text-right font-medium',
                                'text-zinc-500' => $category->products_count > 0,
                                'text-zinc-300' => $category->products_count === 0,
                            ])>
                                {{ trans_choice(':count товар|:count товара|:count товаров', $category->products_count) }}
                            </span>

                            <a
                                href="{{ route('admin.categories.edit', $category) }}"
                                aria-label="Изменить {{ $category->name }}"
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
</x-layouts.admin>
