@props([
    'container' => 'ul',
    'vertical' => false,
])
@php
    /** @var string $container */
    /** @var bool $vertical */
@endphp
<{{ $container }} {{ $attributes->class([
    'nav',
    'flex-column' => $vertical,
]) }}>
    {{ $slot }}
</{{ $container }}>
