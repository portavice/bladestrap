@props([
    'direction' => 'down',
    'nestedInGroup' => false,
])
@php
    /**
     * @var string $direction
     * Possible values: down, down-center, up, up-center, start, end
     */
    $containerClass = 'drop' . $direction;

    /** @var bool $nestedInGroup */

    /** @var ?\Illuminate\View\ComponentSlot $dropdown */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
@if(!$nestedInGroup)
    <div @class($containerClass)>
@endif
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
@if(!$nestedInGroup)
    </div>
@endif
