@props([
    /**
     * @var string $variant
     * Possible values: primary, secondary, success, danger, warning, info, light, dark
     * and any custom variants defined via custom Bootstrap build.
     */
    'variant' => 'info',

    /** @var bool $dismissible */
    'dismissible' => false,
])
<div {{ $attributes
    ->class([
        'alert',
        'alert-' . $variant,
        'alert-dismissible fade show' => $dismissible,
    ])
     ->merge([
         'role' => 'alert',
    ]) }}>{{ $slot }}{{-- avoid whitespace
    --}}@if($dismissible)
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"/>{{-- avoid whitespace
--}}@endif
</div>
