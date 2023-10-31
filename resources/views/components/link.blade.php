@props([
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    'variant' => 'primary',

    /**
     * @var int|string|null $opacity
     * Possible values: 10, 25, 50, 75, 100
     */
    'opacity' => null,

     /**
     * @var int|string|null $opacityHover
     * Possible values: 10, 25, 50, 75, 100
     */
    'opacityHover' => null,
])
<a {{ $attributes
    ->class([
        'link-' . $variant,
        'link-opacity-' . $opacity => isset($opacity),
        'link-opacity-' . $opacityHover . '-hover' => isset($opacityHover),
    ]) }}>{{ $slot }}</a>
