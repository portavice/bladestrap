@props([
    'direction' => 'down',
])
@php
    /**
     * @var string $direction
     * Possible values: down, down-center, up, up-center, start, end
     */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    /** @var ?\Illuminate\View\ComponentSlot $dropdown */

    $containerClass = 'drop' . $direction;
@endphp
<div @class($containerClass)>
    <x-bs::button :attributes="$attributes
        ->class([
            'dropdown-toggle',
        ])
        ->merge([
            'type' => 'button',
            'data-bs-toggle' => 'dropdown',
            'aria-expanded' => 'false',
        ])">{{ $slot }}</x-bs::button>
    @isset($dropdown)
        <ul {{ $dropdown->attributes
            ->class([
                'dropdown-menu',
            ])
            ->merge([
                'aria-labelledby' => $attributes->get('id'),
            ]) }}>
            {{ $dropdown }}
        </ul>
    @endisset
</div>
