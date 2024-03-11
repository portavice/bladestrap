@props([
    'variant' => 'primary',
])
@php
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
@endphp
<span {{ $attributes
    ->class([
        'badge',
        'text-bg-' . $variant,
    ]) }}>{{ $slot }}</span>
