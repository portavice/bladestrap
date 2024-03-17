@props([
    'vertical' => false,
])
@php
    /** @var bool $vertical */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<div {{ $attributes
    ->class([
        $vertical ? 'btn-group-vertical' : 'btn-group',
    ])
    ->merge([
        'role' => 'group'
    ]) }}>{{ $slot }}</div>
