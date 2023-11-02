@props([
    /** @var bool $disabled */
    'disabled' => false,
])
@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    $itemAttributes = $attributes->whereDoesntStartWith('container-');

    $active = $attributes->get('href') === request()?->fullUrl();
@endphp
<li {{ $containerAttributes->class(['nav-item']) }}>
    <a {{ $itemAttributes
        ->class([
            'nav-link',
            'active' => $active,
        ])
        ->merge([
            'aria-current' => $active ? 'page' : null,
            'aria-disabled' => $disabled ? 'true' : null,
            'disabled' => $disabled,
        ]) }}>{{ $slot }}</a>
</li>
