@props([
    'variant' => 'primary',
    'disabled' => false,
])
@php
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark, link,
     * their outline-... variants, and any custom variants defined via custom Bootstrap build.
     */
    /** @var bool $disabled */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
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
