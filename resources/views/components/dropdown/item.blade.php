@props([
    'isSubItem' => false,
    'subItems' => false,
])
<li>{{-- no whitespace
--}}<a {{ $attributes
        ->class([
            'dropdown-item',
            'active' => $attributes->get('href') === request()->fullUrl(),
        ]) }}>{{-- no whitespace
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
