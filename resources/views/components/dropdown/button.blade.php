@props([
    'direction' => 'down',
])
@php
    /**
     * @var string $direction
     * Possible values: down, down-center, up, up-center, start, end
     */
    $containerClass = 'drop' . $direction;

    /** @var ?\Illuminate\View\ComponentSlot $dropdown */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
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
