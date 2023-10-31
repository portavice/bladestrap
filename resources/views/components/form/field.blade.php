@props([
    /** @var ?string $id */
    'id' => null,

    /** @var string $name */
    'name',

    /** @var string $type */
    'type',

    /**
     * Only for checkbox, select, radio.
     * @var $options
     */
    'options',

    /**
     * Only for checkbox, select, radio.
     * @var ?string $containerClass
     * @var ?string $elementClass
     */
    'containerClass' => null,
    'elementClass' => null,

    /** @var string $cast */
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
    use Portavice\Bladestrap\Components\Helpers\ValueHelper;

    /** @var \Illuminate\View\ComponentAttributeBag $attributes */
    $containerAttributes = $attributes->filterAndTransform('container-');
    $labelAttributes = $attributes->filterAndTransform('label-');
    $fieldAttributes = $attributes->whereDoesntStartWith(['container-', 'label-']);

    $dotSyntax = ValueHelper::nameToDotSyntax($name);
    $hasAnyErrors = $errorBag->hasAny($dotSyntax);

    $value = ValueHelper::value($name, $value, $fromQuery, $cast);

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
@endphp
<div {{ $containerAttributes->class([
    config('bladestrap.classes.form.field')
]) }}>
    @if($slot->isNotEmpty())
        <label {{ $labelAttributes
            ->class(config('bladestrap.classes.form.label'))
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
                @isset($containerClass)
                    <div @class([
                        $containerClass,
                    ])>
                @endisset
                    @foreach($options as $optionValue => $optionLabel)
                        @php
                            $optionId = ($id ?? $name) . '-' . $optionValue;
                            $selected = is_array($value)
                                ? in_array($optionValue, $value, true)
                                : $optionValue === $value;
                            $hasAnyErrors = $hasAnyErrors && $errorBag->hasAny([$dotSyntax . '.*']);
                        @endphp
                        <div @class([
                            'form-check',
                            $elementClass,
                        ])>
                            <input {{ $fieldAttributes
                                ->class([
                                    config('bladestrap.classes.form.' . $type),
                                    'is-invalid' => $hasAnyErrors,
                                ])
                                ->merge([
                                    'id' => $optionId,
                                    'name' => $name,
                                    'type' => $type,
                                    'value' => $optionValue,
                                ]) }} @checked($selected)/>
                            <label @class([
                                config('bladestrap.classes.form.' . $type . '_label'),
                            ]) for="{{ $optionId }}">{{ $optionLabel }}</label>
                            @if($loop->last)
                                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag" :showSubErrors="true"/>
                            @endif
                        </div>
                    @endforeach
                @isset($containerClass)
                        @if(count($options) % 2 === 1)
                            <div class="mb-5"></div>
                        @endif
                    </div>
                @endisset
                @break
            @case('range')
                <input {{ $fieldAttributes
                    ->class([
                        config('bladestrap.classes.form.range'),
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
                        config('bladestrap.classes.form.select'),
                        'is-invalid' => $hasAnyErrors,
                    ])
                    ->merge([
                        'id' => $id ?? $name,
                        'name' => $name,
                    ]) }} @disabled($disabled) @required($required)>
                    @foreach($options as $optionValue => $description)
                        <option {{ (new \Illuminate\View\ComponentAttributeBag())
                            ->merge([
                                'value' => $optionValue,
                            ]) }} @selected($value === $optionValue)>{{ $description }}</option>
                    @endforeach
                </select>
                <x-bs::form.feedback name="{{ $name }}" :errorBag="$errorBag"/>
                @break
            @case('textarea')
                <textarea {{ $fieldAttributes
                    ->class([
                        config('bladestrap.classes.form.textarea'),
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
                        config('bladestrap.classes.form.input'),
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
