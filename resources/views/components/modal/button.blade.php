@props([
    'modal',
])
@php
    /** @var string $modal */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<x-bs::button :attributes="$attributes
    ->merge([
        'type' => 'button',
        'data-bs-toggle' => 'modal',
        'data-bs-target' => '#' . $modal,
    ])">{{ $slot }}</x-bs::button>
