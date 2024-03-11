@props([
    'href' => null,
])
@php
    /** @var ?string $href */
    $active = !isset($href) || \Portavice\Bladestrap\Support\ValueHelper::isUrl($href);
@endphp
<li {{ $attributes
    ->class([
        'breadcrumb-item',
        'active' => $active,
    ])
    ->merge([
        'aria-current' => $active ? 'page' : null,
    ]) }}>
    @isset($href)
        <a href="{{ $href }}">{{ $slot }}</a>
    @else
        {{ $slot }}
    @endisset
</li>
