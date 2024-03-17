@props([
    'variant' => 'primary',
    'opacity' => null,
    'opacityHover' => null,
])
@php
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    /**
     * @var int|string|null $opacity
     * Possible values: 10, 25, 50, 75, 100
     */
    /**
     * @var int|string|null $opacityHover
     * Possible values: 10, 25, 50, 75, 100
     */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<a {{ $attributes
    ->class([
        'link-' . $variant,
        'link-opacity-' . $opacity => isset($opacity),
        'link-opacity-' . $opacityHover . '-hover' => isset($opacityHover),
    ]) }}>{{ $slot }}</a>
