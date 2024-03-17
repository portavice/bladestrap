@props([
    'container' => 'li',
    'active' => false,
    'disabled' => false,
    'variant' => null,
])
@php
    /** @var string $container */
    /** @var bool $active */
    /** @var bool $disabled */
    /**
     * @var ?string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<{{ $container }} {{ $attributes
    ->class([
        'list-group-item',
        'list-group-item-' . $variant => isset($variant),
        'active' => $active,
        'disabled' => $disabled,
        'd-flex justify-content-between align-items-center' => isset($end),
    ])
    ->merge([
        'aria-current' => $active ? 'true' : null,
        'aria-disabled' => $disabled ? 'true' : null,
    ]) }}>{{ $slot }}{{-- avoid whitespace
    --}}@isset($end)
        {{ $end }}{{-- avoid whitespace
--}}@endisset
</{{ $container }}>
