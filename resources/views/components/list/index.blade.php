@props([
    /** @var string $container */
    'container' => 'ul',

    /** @var bool $flush */
    'flush' => false,

    /** @var bool $horizontal */
    'horizontal' => false,
])
<{{ $container }} {{ $attributes
    ->class([
        $horizontal ? 'list-group list-group-horizontal' : 'list-group',
        'list-group-flush' => $flush,
    ]) }}>
    {{ $slot }}
</{{ $container }}>
