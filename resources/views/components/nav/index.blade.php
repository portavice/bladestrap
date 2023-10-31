@props([
    /** @var string $container */
    'container' => 'ul',
])
<{{ $container }} {{ $attributes->class('nav') }}>
    {{ $slot }}
</{{ $container }}>
