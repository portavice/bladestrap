@props([
    'method' => 'POST',
])
@php
    /** @string $method */
    $isDefaultMethod = in_array($method, ['GET', 'POST']);

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
@endphp
<form method="{{ $isDefaultMethod ? $method : 'POST' }}" {{ $attributes }}>
    @if($method !== 'GET')
        @csrf
    @endif
    @if(!$isDefaultMethod)
        @method($method)
    @endif
    {{ $slot }}
</form>
