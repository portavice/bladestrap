@props([
    'name',
    'errorBag' => $errors,
    'showSubErrors' => false,
])
@php
    use Portavice\Bladestrap\Support\ValueHelper;

    /** @var string $name */
    /** @var \Illuminate\Support\ViewErrorBag $errorBag */
    /** @var bool $showSubErrors */

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $attributes = $attributes->class([
        'invalid-feedback',
    ]);

    $errorMessages = [];
    $fieldName = ValueHelper::nameToDotSyntax($name);
    if ($errorBag->hasAny([$fieldName])) {
        $errorMessages = $errorBag->get($fieldName);
    }
    if ($showSubErrors && $errorBag->hasAny($fieldName . '.*')) {
        foreach ($errorBag->get($fieldName . '.*') as $errorForItem) {
            $errorMessages = [
                ...$errorMessages,
                ...$errorForItem
            ];
        }
    }
@endphp
@if(count($errorMessages) > 0)
    <div role="alert" {{ $attributes }}>
        @foreach($errorMessages as $error)
            {{ $error }}
        @endforeach
    </div>
@endif
