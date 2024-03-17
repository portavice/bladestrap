@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<li {{ $attributes
    ->class([
        'dropdown-header',
    ]) }}>{{ $slot }}</li>
