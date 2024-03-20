@props([
    'direction' => 'down',
    'disabled' => false,
])
@php
    /**
     * @var string $direction
     * Possible values: down, down-center, up, up-center, start, end
     * May be used only in combination with dropdown slot.
     */
    /** @var bool $disabled */

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    $itemAttributes = $attributes->whereDoesntStartWith('container-');

    $active = \Portavice\Bladestrap\Support\ValueHelper::isUrl($attributes->get('href'));

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
        'drop' . $direction => $hasDropdown,
    ]) }}>
    <a {{ $itemAttributes
        ->class([
            'nav-link',
            'active' => $active,
            'disabled' => $disabled,
            'dropdown-toggle' => $hasDropdown,
        ])
        ->merge([
            'aria-current' => $active ? 'page' : null,
            'aria-disabled' => $disabled ? 'true' : null,
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
