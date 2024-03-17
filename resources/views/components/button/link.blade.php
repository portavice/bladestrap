@props([
    'variant' => 'primary',
    'disabled' => false,
])
@php
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    /** @var bool $disabled */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<a {{ $attributes
    ->class([
        'btn',
        'btn-' . $variant,
        'disabled' => $disabled,
    ])
     ->merge([
         'aria-disabled' => $disabled ? 'true' : null,
         'role' => $attributes->get('href') === '#' ? 'button' : null,
         'tabindex' => $disabled && $attributes->has('href') ? '-1' : null,
    ]) }}>{{ $slot }}</a>
