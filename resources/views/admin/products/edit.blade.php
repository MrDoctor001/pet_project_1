<x-layouts.admin :title="$product->name">
    <x-slot:actions>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-zinc-500 transition hover:text-zinc-900">← Все товары</a>

        @if ($product->is_active)
            <a
                href="{{ route('products.show', $product) }}"
                target="_blank"
                class="rounded-xl border border-zinc-300 bg-white px-4 py-2 text-sm font-semibold transition hover:border-zinc-400"
            >
                Открыть в магазине
            </a>
        @endif
    </x-slot:actions>

    @include('admin.products.form')

    @if (Route::has('admin.products.destroy'))
        <section class="flex flex-wrap items-center justify-between gap-4 rounded-3xl border border-red-200 bg-red-50/50 p-6">
            <div class="flex flex-col gap-1">
                <h2 class="font-bold text-red-700">Удалить товар</h2>
                <p class="text-sm text-red-600/80">Товар пропадёт из каталога навсегда. В старых заказах он останется под своим названием.</p>
            </div>

            <form
                method="POST"
                action="{{ route('admin.products.destroy', $product) }}"
                onsubmit="return confirm('Удалить «{{ addslashes($product->name) }}»? Это действие нельзя отменить.')"
            >
                @csrf
                @method('DELETE')
                <button type="submit" class="rounded-xl bg-red-600 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-red-500">
                    Удалить
                </button>
            </form>
        </section>
    @endif
</x-layouts.admin>
