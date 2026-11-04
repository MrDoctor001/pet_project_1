<x-layouts.admin title="Новый товар">
    <x-slot:actions>
        <a href="{{ route('admin.products.index') }}" class="text-sm font-semibold text-zinc-500 transition hover:text-zinc-900">← Все товары</a>
    </x-slot:actions>

    @include('admin.products.form', ['product' => null])
</x-layouts.admin>
