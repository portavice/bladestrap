@props([
    'container' => 'ul',
    'vertical' => false,
])
@php
    /** @var string $container */
    /** @var bool $vertical */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<{{ $container }} {{ $attributes->class([
    'nav',
    'flex-column' => $vertical,
]) }}>
    {{ $slot }}
</{{ $container }}>
