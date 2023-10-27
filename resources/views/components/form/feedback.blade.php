@props([
    /** @var string $name */
    'name',

    /** @var \Illuminate\Support\ViewErrorBag $errorBag */
    'errorBag' => $errors,

    /** @var bool $showSubErrors */
    'showSubErrors' => false,
])
@php
    use Portavice\Bladestrap\Components\Helpers\ValueHelper;

    $attributes = $attributes->class([
        config('bladestrap.classes.form.invalid-feedback'),
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
