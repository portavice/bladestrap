@props([
    'id' => null,
    'name',
    'type',
    'options',
    'allowHtml' => false,
    'nestedInGroup' => false,
    'cast' => null,
    'value' => null,
    'fromQuery' => false,
    'errorBag' => $errors,
    'disabled' => false,
    'readonly' => false,
    'required' => false,
    'markAsRequired' => config('bladestrap.form.field.mark_as_required'),
])
@php
    use Portavice\Bladestrap\Support\Options;
    use Portavice\Bladestrap\Support\ValueHelper;

    /** @var ?string $id */
    /** @var string $name */
    /** @var string $type */
    /**
     * Only for checkbox, select, radio.
     * @var iterable|Illuminate\Support\Collection|Options $options
     */
    /**
     * @var bool $allowHtml
     * Only affects labels of options.
     */
     /**
     * @var bool $nestedInGroup
     * Whether the form field is nested in another form field (via prepend or append slot)
     */
    if ($nestedInGroup && $slot->isNotEmpty()) {
        throw new RuntimeException('Attribute nestedInGroup is only allowed with empty slot!');
    }
    /** @var ?string $cast */
    /**
     * The preset value, may be automatically overwritten by an old() value if old values are present.
     * @var ?mixed $value
     */
    /**
     * @var bool $fromQuery
     * If enabled, value is extracted from the query parameter of the URL.
     */
    /** @var \Illuminate\Support\ViewErrorBag $errorBag */
    /** @var bool $disabled */
    /** @var bool $readonly */
    /** @var bool $required */
    /** @var bool $markAsRequired */

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    /** @var \Illuminate\View\ComponentAttributeBag $containerAttributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    if ($slot->isNotEmpty()) {
        $containerAttributes = $containerAttributes->class([
            config('bladestrap.form.field.class'),
        ]);
    }
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
    $hasInputGroupContainer = !$nestedInGroup && (isset($prependText) || isset($appendText));

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
            return $options instanceof Options
                ? $options->getAttributes($optionValue)
                : new \Illuminate\View\ComponentAttributeBag();
        };

        if ($options instanceof Options) {
            $cast = $cast ?? $options->getCast();
        }
    }

    // Override selected value and apply cast.
    if (!$disabled) {
        $value = ValueHelper::value($name, $value, $fromQuery, $cast);
    }

    $showFeedback = true;
@endphp
@if($type === 'hidden')
    <input {{ $fieldAttributes
        ->merge([
            'id' => $id ?? $name,
            'name' => $name,
            'type' => $type,
            'value' => $value,
        ]) }}/>
@else
    @if(isset($hint) || $slot->isNotEmpty())
        <div {{ $containerAttributes }}>
            @if($slot->isNotEmpty())
                <label {{ $labelAttributes
                    ->class([
                        'form-label',
                    ])
                    ->merge([
                        'for' => $id ?? $name,
                    ]) }}>{{ $slot }}@if($required && $markAsRequired) *@endif</label>
            @endif
    @endif
        @if($hasInputGroupContainer)
            <div @class([
                'input-group',
                'has-validation' => $hasAnyErrors,
            ])>
        @endif
            @isset($prependText)
                @if($prependText->attributes->get('container', true) === false)
                    {{ $prependText }}
                @else
                    <label {{ $prependText->attributes
                        ->class([
                            'input-group-text',
                        ])
                        ->merge([
                            'for' => $id ?? $name,
                        ]) }}>{{ $prependText }}</label>
                @endif
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

                        // Feedback directly outputted in case block.
                        $showFeedback = false;
                    @endphp
                    @if(!$checkContainerAttributes->isEmpty())
                        <div {{ $checkContainerAttributes }}>
                    @endif
                        @foreach($options as $optionValue => $optionLabel)
                            @php
                                $optionId = ($id ?? $name) . '-' . $optionValue;
                                $hasAnyErrors = $hasAnyErrors || $errorBag->hasAny([$dotSyntax . '.*']);

                                $attributesForOption = $getAttributesForOption($optionValue);
                                /** @var \Illuminate\View\ComponentAttributeBag $checkContainerAttributesForOption */
                                $checkContainerAttributesForOption = $attributesForOption->filterAndTransform('check-');
                            @endphp
                            <div {{ $checkContainerAttributesForOption
                                ->class([
                                    $checkClass,
                                ]) }}>
                                <input {{ $fieldAttributes
                                    ->merge($attributesForOption->whereDoesntStartWith('check-')->getAttributes())
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
                                ]) for="{{ $optionId }}">@if($allowHtml){!! $optionLabel !!}@else{{ $optionLabel }}@endif</label>
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
            @endswitch
            @isset($appendText)
                @if($appendText->attributes->get('container', true) === false)
                    {{ $appendText }}
                @else
                    <label {{ $appendText->attributes
                        ->class([
                            'input-group-text',
                        ])
                        ->merge([
                            'for' => $id ?? $name,
                        ]) }}>{{ $appendText }}</label>
                @endif
            @endisset
            @if($showFeedback)
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
            @endif
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
    @if(isset($hint) || $slot->isNotEmpty())
        </div>
    @endif
@endif
