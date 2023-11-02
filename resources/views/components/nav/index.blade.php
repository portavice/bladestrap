@props([
    /** @var string $container */
    'container' => 'ul',

    /** @var bool $vertical */
    'vertical' => false,
])
<{{ $container }} {{ $attributes->class([
    'nav',
    'flex-column' => $vertical,
]) }}>
    {{ $slot }}
</{{ $container }}>
