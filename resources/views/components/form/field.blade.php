@props([
    /** @var ?string $id */
    'id' => null,

    /** @var string $name */
    'name',

    /** @var string $type */
    'type',

    /**
     * Only for checkbox, select, radio.
     * @var iterable|Illuminate\Support\Collection|OptionCollection $options
     */
    'options',

    /** @var ?string $cast */
    'cast' => null,

    /**
     * The preset value, may be automatically overwritten by an old() value if old values are present.
     * @var ?mixed $value
     */
    'value' => null,

    /*
     * If enabled, value is extracted from the query parameter of the URL.
     * @var bool $fromQuery
     */
    'fromQuery' => false,

    /** @var \Illuminate\Support\ViewErrorBag $errorBag */
    'errorBag' => $errors,

    /** @var bool $disabled */
    'disabled' => false,

    /** @var bool $readonly */
    'readonly' => false,

    /** @var bool $required */
    'required' => false,
])
@php
    use Portavice\Bladestrap\Support\OptionCollection;
    use Portavice\Bladestrap\Support\ValueHelper;

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    /** @var \Illuminate\View\ComponentAttributeBag $containerAttributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    /** @var \Illuminate\View\ComponentAttributeBag $labelAttributes */
    $labelAttributes = $attributes->filterAndTransform('label-');
    /** @var \Illuminate\View\ComponentAttributeBag $fieldAttributes */
    $fieldAttributes = $attributes->whereDoesntStartWith([
        'container-',
        'label-',
        'check-container-',
        'check-label-',
    ]);

    $dotSyntax = ValueHelper::nameToDotSyntax($name);
    $hasAnyErrors = $errorBag->hasAny($dotSyntax);

    /** @var ?\Illuminate\View\ComponentSlot $prependText */
    /** @var ?\Illuminate\View\ComponentSlot $appendText */
    $hasInputGroupContainer = isset($prependText) || isset($appendText);

    /** @var ?\Illuminate\View\ComponentSlot $hint */
    if (isset($hint)) {
        $hintId = ($id ?? $name) . '-hint';
        $fieldAttributes = $fieldAttributes->merge([
            'aria-describedby' => $hintId,
        ]);
    }

    if (isset($options)) {
        /** @var \Closure(int|string): \Illuminate\View\ComponentAttributeBag $getAttributesForOption */
        $getAttributesForOption = static function ($optionValue) use ($options) {
            return $options instanceof OptionCollection
                ? $options->getAttributes($optionValue)
                : new \Illuminate\View\ComponentAttributeBag();
        };

        if ($options instanceof OptionCollection) {
            $cast = $cast ?? $options->getCast();
        }
    }

    // Override selected value and apply cast.
    $value = ValueHelper::value($name, $value, $fromQuery, $cast);
@endphp
<div {{ $containerAttributes->class([
    config('bladestrap.form.field.class'),
]) }}>
    @if($slot->isNotEmpty())
        <label {{ $labelAttributes
            ->class([
                'form-label',
            ])
            ->merge([
                'for' => $id ?? $name,
            ]) }}>{{ $slot }}</label>
    @endif
    @if($hasInputGroupContainer)
        <div @class([
            'input-group',
            'has-validation' => $hasAnyErrors,
        ])>
    @endif
        @isset($prependText)
            <label for="{{ $id ?? $name }}" {{ $prependText->attributes->class(['input-group-text']) }}>{{ $prependText }}</label>
        @endisset
        @switch($type)
            @case('checkbox')
            @case('radio')
            @case('switch')
                @php
                    /** @var \Illuminate\View\ComponentAttributeBag $checkContainerAttributes */
                    $checkContainerAttributes = $attributes->filterAndTransform('check-container-');
                    /** @var \Illuminate\View\ComponentAttributeBag $checkLabelAttributes */
                    $checkLabelAttributes = $attributes->filterAndTransform('check-label-');

                    [$type, $checkClass, $role] = match ($type) {
                        'switch' => ['checkbox', 'form-check form-switch', 'switch'],
                        default => [$type, 'form-check', null],
                    };
                @endphp
                @if(!$checkContainerAttributes->isEmpty())
                    <div {{ $checkContainerAttributes }}>
                @endif
                    @foreach($options as $optionValue => $optionLabel)
                        @php
                            $optionId = ($id ?? $name) . '-' . $optionValue;
                            $hasAnyErrors = $hasAnyErrors && $errorBag->hasAny([$dotSyntax . '.*']);

                            $attributesForOption = $getAttributesForOption($optionValue);
                            /** @var \Illuminate\View\ComponentAttributeBag $checkContainerAttributesForOption */
                            $checkContainerAttributesForOption = $attributesForOption->filterAndTransform('check-');
                        @endphp
                        <div {{ $checkContainerAttributesForOption
                            ->class([
                                $checkClass,
                            ]) }}>
                            <input {{ $fieldAttributes->merge($attributesForOption->whereDoesntStartWith('check-')->getAttributes())
                                ->class([
                                    'form-check-input',
                                    'is-invalid' => $hasAnyErrors,
                                ])
                                ->merge([
                                    'id' => $optionId,
                                    'name' => $name,
                                    'role' => $role,
                                    'type' => $type,
                                    'value' => $optionValue,
                                ]) }} @checked(ValueHelper::isActive($optionValue, $value)) @disabled($disabled) @readonly($readonly) @required($required)/>
                            <label @class([
                                'form-check-label',
                            ]) for="{{ $optionId }}">{{ $optionLabel }}</label>
                            @if($loop->last)
                                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag" :showSubErrors="true"/>
                            @endif
                        </div>
                    @endforeach
                @if(!$checkContainerAttributes->isEmpty())
                    </div>
                @endif
                @break
            @case('range')
                <input {{ $fieldAttributes
                    ->class([
                        'form-range',
                        'is-invalid' => $hasAnyErrors,
                    ])
                    ->merge([
                        'id' => $id ?? $name,
                        'name' => $name,
                        'type' => $type,
                        'value' => $value,
                    ]) }} @disabled($disabled) @readonly($readonly) @required($required)/>
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
                @break
            @case('select')
                <select {{ $attributes
                    ->class([
                        'form-select',
                        'is-invalid' => $hasAnyErrors,
                    ])
                    ->merge([
                        'id' => $id ?? $name,
                        'name' => $name,
                    ]) }} @disabled($disabled) @readonly($readonly) @required($required)>
                    @foreach($options as $optionValue => $description)
                        <option {{ $getAttributesForOption($optionValue)
                            ->merge([
                                'value' => $optionValue,
                            ]) }} @selected(ValueHelper::isActive($optionValue, $value))>{{ $description }}</option>
                    @endforeach
                </select>
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
                @break
            @case('textarea')
                <textarea {{ $fieldAttributes
                    ->class([
                        'form-control',
                        'is-invalid' => $hasAnyErrors,
                    ])
                    ->merge([
                        'id' => $id ?? $name,
                        'name' => $name,
                    ]) }} @disabled($disabled) @readonly($readonly) @required($required)>{{ $value }}</textarea>
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
                @break
            @default
                <input {{ $fieldAttributes
                    ->class([
                        'form-control',
                        'is-invalid' => $hasAnyErrors,
                    ])
                    ->merge([
                        'id' => $id ?? $name,
                        'name' => $name,
                        'type' => $type,
                        'value' => $value,
                    ]) }} @disabled($disabled) @readonly($readonly) @required($required)/>
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
        @endswitch
        @isset($appendText){{-- avoid whitespace
            --}}<label for="{{ $id ?? $name }}" {{ $appendText->attributes->class(['input-group-text']) }}>{{ $appendText }}</label>
        @endisset
    @if($hasInputGroupContainer)
        </div>
    @endif
    @isset($hint)
        <div {{ $hint->attributes
            ->class([
                'form-text',
            ])
            ->merge([
                'id' => $hintId,
            ]) }}>{{ $hint }}</div>
    @endisset
</div>
