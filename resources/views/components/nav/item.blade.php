@props([
    /** @var bool $disabled */
    'disabled' => false,
])
@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    $itemAttributes = $attributes->whereDoesntStartWith('container-');

    $active = $attributes->get('href') === request()?->fullUrl();

    /** @var ?\Illuminate\View\ComponentSlot $dropdown */
    $hasDropdown = isset($dropdown);
    if ($hasDropdown) {
        $itemAttributes = $itemAttributes->merge([
            'href' => $itemAttributes->get('href', '#'),
            'role' => 'button',
            'data-bs-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false',
        ]);
    }
@endphp
<li {{ $containerAttributes
    ->class([
        'nav-item',
        'dropdown' => $hasDropdown,
    ]) }}>
    <a {{ $itemAttributes
        ->class([
            'nav-link',
            'active' => $active,
            'dropdown-toggle' => $hasDropdown,
        ])
        ->merge([
            'aria-current' => $active ? 'page' : null,
            'aria-disabled' => $disabled ? 'true' : null,
            'disabled' => $disabled,
        ]) }}>{{ $slot }}</a>
    @if($hasDropdown)
        <ul {{ $dropdown->attributes
            ->class([
                'dropdown-menu',
            ])
            ->merge([
                'aria-labelledby' => $itemAttributes->get('id'),
            ]) }}>{{ $dropdown }}</ul>
    @endif
</li>
