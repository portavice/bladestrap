@props([
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark, link,
     * their outline-... variants, and any custom variants defined via custom Bootstrap build.
     */
    'variant' => 'primary',

    /** @var bool $disabled */
    'disabled' => false,
])
<button {{ $attributes
    ->class([
        'btn',
        'btn-' . $variant,
        'disabled' => $disabled,
    ])
     ->merge([
         'aria-disabled' => $disabled ? 'true' : null,
         'tabindex' => $disabled ? '-1' : null,
    ])}} @disabled($disabled)>{{ $slot }}</button>
