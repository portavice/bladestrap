@props([
    /** @var ?string $href */
    'href' => null,
])
@php
    $active = !isset($href) || $href === request()?->fullUrl();
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
