@props([
    /** @string $method */
    'method' => 'POST',
])
@php
    $isDefaultMethod = in_array($method, ['GET', 'POST']);
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
