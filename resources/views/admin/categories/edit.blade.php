<x-layouts.admin :title="$category->name">
    <x-slot:actions>
        <a href="{{ route('admin.categories.index') }}" class="text-sm font-semibold text-zinc-500 transition hover:text-zinc-900">← Все категории</a>

        <a
            href="{{ route('catalog.category', $category) }}"
            target="_blank"
            class="rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold transition hover:border-zinc-400"
        >
            Открыть в магазине
        </a>
    </x-slot:actions>

    <form
        method="POST"
        action="{{ route('admin.categories.update', $category) }}"
        class="flex max-w-2xl flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8"
    >
        @csrf
        @method('PUT')

        <div class="flex flex-col gap-1.5">
            <x-form-field name="name" label="Название" :value="$category->name" required autofocus />
            @error('slug')
                <p class="text-sm text-red-600">Категория с таким названием уже есть</p>
            @enderror
            <p class="text-xs text-zinc-400">Адрес в магазине обновится по новому названию.</p>
        </div>

        <button
            type="submit"
            class="self-start rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500"
        >
            Сохранить изменения
        </button>
    </form>

    @if (Route::has('admin.categories.destroy'))
        <section class="flex max-w-2xl flex-wrap items-center justify-between gap-4 rounded-3xl border border-red-200 bg-red-50/50 p-6">
            <div class="flex flex-col gap-1">
                <h2 class="font-bold text-red-700">Удалить категорию</h2>
                @if ($category->products_count > 0)
                    <p class="text-sm text-red-600/80">
                        Нельзя удалить: в категории {{ trans_choice(':count товар|:count товара|:count товаров', $category->products_count) }}.
                        Сначала перенесите их в другую категорию или удалите.
                    </p>
                @else
                    <p class="text-sm text-red-600/80">Категория пустая, её можно удалить.</p>
                @endif
                @error('category')
                    <p class="text-sm font-semibold text-red-700">{{ $message }}</p>
                @enderror
            </div>

            @if (! $category->products_count)
                <form
                    method="POST"
                    action="{{ route('admin.categories.destroy', $category) }}"
                    onsubmit="return confirm('Удалить категорию «{{ addslashes($category->name) }}»?')"
                >
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">
                        Удалить
                    </button>
                </form>
            @endif
        </section>
    @endif
</x-layouts.admin>
