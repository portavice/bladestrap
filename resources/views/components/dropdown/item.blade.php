@props([
    'isSubItem' => false,
    'subItems' => false,
])
@php
    $active = $attributes->get('href') === request()?->fullUrl();
@endphp
<li>{{-- no whitespace
--}}<a {{ $attributes
        ->class([
            'dropdown-item',
            'active' => $active,
        ])
        ->merge([
            'aria-current' => $active ? 'true' : null,
        ])}}>{{-- no whitespace
    --}}@if($isSubItem){{-- no whitespace
            --}}<span class="ms-4">{{ $slot }}</span>{{-- no whitespace
        --}}@else{{-- no whitespace
            --}}{{ $slot }}{{-- no whitespace
    --}}@endif{{-- no whitespace
--}}</a>{{-- no whitespace
--}}@if($subItems)
        <ul class="list-unstyled small">
            {{ $subItems }}
        </ul>
    @endif
</li>
