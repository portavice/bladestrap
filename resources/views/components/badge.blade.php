@props([
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    'variant' => 'primary',
])
<span {{ $attributes
    ->class([
        'badge',
        'text-bg-' . $variant,
    ]) }}>{{ $slot }}</span>
