@props(['name', 'label', 'type' => 'text', 'required' => false])

<div class="flex flex-col gap-1.5">
    <label for="{{ $name }}" class="text-sm font-medium text-zinc-900">
        {{ $label }}
        @unless ($required)
            <span class="font-normal text-zinc-400">(необязательно)</span>
        @endunless
    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name) }}"
        @required($required)
        @error($name) aria-invalid="true" aria-describedby="{{ $name }}-error" @enderror
        {{ $attributes->class([
            'rounded-xl border bg-white px-4 py-3 text-sm transition focus:ring-4 focus:outline-none',
            'border-zinc-300 focus:border-brand-500 focus:ring-brand-500/15' => ! $errors->has($name),
            'border-red-400 focus:border-red-500 focus:ring-red-500/15' => $errors->has($name),
        ]) }}
    >

    @error($name)
        <p id="{{ $name }}-error" class="text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
