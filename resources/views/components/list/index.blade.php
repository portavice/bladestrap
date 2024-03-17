@props([
    'container' => 'ul',
    'flush' => false,
    'horizontal' => false,
])
@php
    /** @var string $container */
    /** @var bool $flush */
    /** @var bool $horizontal */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<{{ $container }} {{ $attributes
    ->class([
        $horizontal ? 'list-group list-group-horizontal' : 'list-group',
        'list-group-flush' => $flush,
    ]) }}>
    {{ $slot }}
</{{ $container }}>
