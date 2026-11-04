@php
    $inputClasses = 'rounded-xl border bg-white px-4 py-3 text-sm transition focus:ring-4 focus:outline-none';
    $validClasses = 'border-zinc-300 focus:border-brand-500 focus:ring-brand-500/15';
    $invalidClasses = 'border-red-400 focus:border-red-500 focus:ring-red-500/15';
@endphp

<form
    method="POST"
    action="{{ $product ? route('admin.products.update', $product) : route('admin.products.store') }}"
    enctype="multipart/form-data"
    class="grid gap-6 lg:grid-cols-[1fr_20rem]"
>
    @csrf
    @if ($product)
        @method('PUT')
    @endif

    <section class="flex flex-col gap-5 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5 sm:p-8">
        <div class="flex flex-col gap-1.5">
            <x-form-field name="name" label="Название" :value="$product?->name" required autofocus />
            @error('slug')
                <p class="text-sm text-red-600">Товар с похожим названием уже есть — измените название</p>
            @enderror
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="category_id" class="text-sm font-medium text-zinc-900">Категория</label>
            <select
                id="category_id"
                name="category_id"
                required
                @class([$inputClasses, $validClasses => ! $errors->has('category_id'), $invalidClasses => $errors->has('category_id')])
            >
                <option value="" disabled @selected(! old('category_id', $product?->category_id))>Выберите категорию</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" @selected((int) old('category_id', $product?->category_id) === $category->id)>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-5 sm:grid-cols-2">
            <x-form-field name="price" label="Цена, ₸" type="number" :value="$product?->price" min="0" step="0.01" inputmode="decimal" required />
            <x-form-field name="stock" label="Остаток, шт." type="number" :value="$product?->stock ?? 0" min="0" step="1" inputmode="numeric" required />
        </div>

        <div class="flex flex-col gap-1.5">
            <label for="description" class="text-sm font-medium text-zinc-900">
                Описание <span class="font-normal text-zinc-400">(необязательно)</span>
            </label>
            <textarea
                id="description"
                name="description"
                rows="6"
                @class([$inputClasses, $validClasses => ! $errors->has('description'), $invalidClasses => $errors->has('description')])
            >{{ old('description', $product?->description) }}</textarea>
            @error('description')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
    </section>

    <div class="flex flex-col gap-6">
        <section class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5">
            <h2 class="font-bold">Фото</h2>

            @if ($product)
                <x-product-image :product="$product" class="aspect-square rounded-2xl" />
            @endif

            <input
                type="file"
                name="image"
                accept="image/jpeg,image/png,image/webp"
                aria-label="Фото товара"
                class="text-sm text-zinc-500 file:mr-3 file:rounded-lg file:border-0 file:bg-brand-50 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-brand-700 hover:file:bg-brand-100"
            >
            <p class="text-xs text-zinc-400">
                JPG, PNG или WEBP, до 2 МБ.
                @if ($product?->image)
                    Новое фото заменит текущее.
                @endif
            </p>
            @error('image')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </section>

        <section class="flex flex-col gap-4 rounded-3xl bg-white p-6 shadow-sm ring-1 ring-zinc-900/5">
            <input type="hidden" name="is_active" value="0">
            <label class="flex items-start gap-3">
                <input
                    type="checkbox"
                    name="is_active"
                    value="1"
                    @checked(old('is_active', $product?->is_active ?? true))
                    class="mt-0.5 size-5 rounded border-zinc-300 accent-brand-600"
                >
                <span class="flex flex-col">
                    <span class="text-sm font-semibold">Показывать в магазине</span>
                    <span class="text-xs text-zinc-500">Скрытый товар не виден покупателям, но остаётся в админке</span>
                </span>
            </label>

            <button
                type="submit"
                class="rounded-xl bg-brand-600 px-6 py-3 text-sm font-semibold text-white shadow-sm shadow-brand-600/30 transition hover:bg-brand-500 focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-600"
            >
                {{ $product ? 'Сохранить изменения' : 'Добавить товар' }}
            </button>
        </section>
    </div>
</form>
