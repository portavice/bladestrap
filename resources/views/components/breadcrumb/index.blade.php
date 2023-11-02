@php
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    $listAttributes = $attributes->whereDoesntStartWith('container-');
@endphp
<nav {{ $containerAttributes }} aria-label="breadcrumb">
    <ol {{ $listAttributes->class('breadcrumb') }}>
        {{ $slot }}
    </ol>
</nav>
