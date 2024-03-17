@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<div {{ $attributes
    ->class([
        'btn-toolbar',
    ])
    ->merge([
        'role' => 'toolbar',
    ]) }}>{{ $slot }}</div>
