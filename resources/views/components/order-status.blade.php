@props(['status'])

<span {{ $attributes->class([
    'rounded-full px-3 py-1 text-xs font-semibold whitespace-nowrap',
    'bg-brand-50 text-brand-700' => $status === \App\Enums\OrderStatus::New,
    'bg-amber-50 text-amber-700' => $status === \App\Enums\OrderStatus::Processing,
    'bg-sky-50 text-sky-700' => $status === \App\Enums\OrderStatus::Shipped,
    'bg-emerald-50 text-emerald-700' => $status === \App\Enums\OrderStatus::Completed,
    'bg-zinc-100 text-zinc-500' => $status === \App\Enums\OrderStatus::Canceled,
]) }}>
    {{ $status->label() }}
</span>
