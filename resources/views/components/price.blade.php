@props(['amount'])

@php
    $amount = (float) $amount;
    $decimals = floor($amount) === $amount ? 0 : 2;
@endphp

<span {{ $attributes->class('whitespace-nowrap') }}>{{ number_format($amount, $decimals, ',', ' ') }} ₸</span>
