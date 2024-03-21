@props([
    'id',
    'centered' => false,
    'fade' => true,
    'fullScreen' => false,
    'scrollable' => false,
    'staticBackdrop' => false,
    'closeButton' => true,
    'closeButtonTitle' => 'Close',
])
@php
    /** @var string $id */
    $labelId = $id . 'Label';
    /** @var bool $centered */
    /** @var bool $fade */
    /** @var bool $scrollable */
    /** @var bool|string $fullScreen */
    /** @var bool $staticBackdrop */
    /** @var string|bool $closeButton */
    $closeButton = $closeButton === true ? 'secondary' : $closeButton;
    $showCloseButtonInFooter = is_string($closeButton);
    /** @var string $closeButtonTitle */
    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    /** @var ?\Illuminate\View\ComponentSlot $title */
    /** @var ?\Illuminate\View\ComponentSlot $closeButton */
    /** @var ?\Illuminate\View\ComponentSlot $action */
@endphp
<div {{ $attributes
    ->class([
        'modal',
        'fade' => $fade,
    ])
    ->merge([
        'id' => $id,
        'tabindex' => -1,
        'aria-labelledby' => $id . 'Label',
        'aria-hidden' => 'true',
        'data-bs-backdrop' => $staticBackdrop ? 'static' : null,
        'data-bs-keyboard' => $staticBackdrop ? 'false' : null,
    ])}}>
    <div @class([
        'modal-dialog',
        'modal-fullscreen' => $fullScreen === true,
        'modal-fullscreen-' . $fullScreen . '-down' => is_string($fullScreen),
        'modal-dialog-centered' => $centered,
        'modal-dialog-scrollable' => $scrollable,
    ])>
        <div class="modal-content">
            <div class="modal-header">
                @if(isset($title) && $title instanceof \Illuminate\View\ComponentSlot)
                    @php
                        $container = $title->attributes->get('container', 'h1');
                    @endphp
                    <{{ $container }} {{ $title->attributes
                        ->except([
                            'container',
                        ])
                        ->class([
                            'modal-title',
                        ])
                        ->merge([
                            'id' => $labelId,
                        ]) }}>{{ $title }}</{{ $container }}>
            @endif
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ $closeButtonTitle }}"></button>
        </div>
        <div class="modal-body">{{ $slot }}</div>
        @if($showCloseButtonInFooter || (isset($footer) && $footer instanceof \Illuminate\View\ComponentSlot))
            @php
                $footer = $footer ?? new \Illuminate\View\ComponentSlot();
            @endphp
            <div {{ $footer->attributes
                    ->class([
                        'modal-footer',
                    ]) }}>
                @if($showCloseButtonInFooter)
                    <x-bs::button :variant="$closeButton" {{ $attributes
                                ->merge([
                                    'type' => 'button',
                                    'data-bs-dismiss' => 'modal',
                                ]) }}>{{ $closeButtonTitle }}</x-bs::button>
                @endif{{--
                    --}}{{ $footer }}</div>
        @endif
    </div>
</div>
</div>
