@props([
    'vertical' => false,
])
@php
    /** @var bool $vertical */
@endphp
<div {{ $attributes
    ->class([
        $vertical ? 'btn-group-vertical' : 'btn-group',
    ])
    ->merge([
        'role' => 'group'
    ]) }}>{{ $slot }}</div>
