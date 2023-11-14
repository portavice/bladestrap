<?php

namespace Portavice\Bladestrap\Support;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;

class OptionCollection implements \IteratorAggregate
{
    /**
     * @var array<int|string,ComponentAttributeBag>
     */
    private array $attributesForOption = [];

    private ?string $cast = null;

    public function __construct(
        /**
         * @var array<int|string,string>
         */
        private array $options,
    ) {
    }

    public function addAttributes(int|string $optionValue, array|string $attributes): self
    {
        $this->setAttributes($optionValue, $this->getAttributes($optionValue)->merge(Arr::wrap($attributes)));

        return $this;
    }

    public function append(
        int|string $optionValue,
        int|string $label,
        array|ComponentAttributeBag|null $attributes = null
    ): self {
        $this->options += [$optionValue => $label];
        if ($attributes !== null) {
            $this->setAttributes($optionValue, $attributes);
        }

        return $this;
    }

    public function getAttributes(int|string $optionValue): ComponentAttributeBag
    {
        return $this->attributesForOption[$optionValue] ?? new ComponentAttributeBag([]);
    }

    public function getCast(): ?string
    {
        return $this->cast;
    }

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->options);
    }

    public function prepend(
        int|string $optionValue,
        int|string $label,
        array|ComponentAttributeBag|null $attributes = null
    ): self {
        $this->options = [$optionValue => $label] + $this->options;
        if ($attributes !== null) {
            $this->setAttributes($optionValue, $attributes);
        }

        return $this;
    }

    public function setAttributes(int|string $optionValue, array|ComponentAttributeBag $attributes): self
    {
        $this->attributesForOption[$optionValue] = self::wrapAttributes($attributes);

        return $this;
    }

    public function toArray(): array
    {
        return $this->options;
    }

    private static function wrapAttributes(array|ComponentAttributeBag $attributes): ComponentAttributeBag
    {
        return is_array($attributes)
            ? new ComponentAttributeBag($attributes)
            : $attributes;
    }

    /**
     * @param array<int|string,int|string> $array
     * @param ?\Closure(<int|string>,int|string): ComponentAttributeBag $attributeProvider
     */
    public static function fromArray(
        array $array,
        \Closure|null $attributeProvider = null
    ): self {
        $optionCollection = new self($array);

        if ($attributeProvider instanceof \Closure) {
            foreach ($array as $optionValue => $label) {
                $optionCollection->setAttributes($optionValue, $attributeProvider($optionValue, $label));
            }
        }

        return $optionCollection;
    }

    /**
     * @param class-string|array<\BackedEnum> $enum
     * @param \Closure(<\BackedEnum>): (int|string)|string $labelProvider
     * @param ?\Closure(<\BackedEnum>): ComponentAttributeBag $attributeProvider
     */
    public static function fromEnum(
        array|string $enum,
        \Closure|string $labelProvider = 'value',
        \Closure|null $attributeProvider = null
    ): self {
        $enumCases = self::enumCases($enum);
        if (is_string($labelProvider)) {
            $labelProvider = match ($labelProvider) {
                'name' => static fn ($enumCase) => $enumCase->name,
                'value' => static fn ($enumCase) => $enumCase->value,
                default => static fn ($enumCase) => $enumCase->$labelProvider(),
            };
        }

        $optionArray = [];
        foreach ($enumCases as $enumCase) {
            $optionArray[$enumCase->value] = $labelProvider($enumCase);
        }
        $optionCollection = new self($optionArray);
        if (is_int(array_key_first($optionArray))) {
            $optionCollection->cast = 'int';
        }

        if ($attributeProvider instanceof \Closure) {
            foreach ($enumCases as $enumCase) {
                $optionCollection->setAttributes($enumCase->value, $attributeProvider($enumCase));
            }
        }

        return $optionCollection;
    }

    /**
     * @param class-string|array<\BackedEnum> $enum
     */
    private static function enumCases(array|string $enum): array
    {
        if (is_array($enum)) {
            return $enum;
        }

        if (!enum_exists($enum)) {
            throw new \BadMethodCallException('Enum expected, but ' . $enum . ' found');
        }

        return $enum::cases();
    }

    /**
     * @param Model[]|Collection<Model> $models
     * @param \Closure(<Model>): (int|string)|string $labelProvider
     * @param ?\Closure(<Model>): ComponentAttributeBag $attributeProvider
     */
    public static function fromModels(
        array|Collection $models,
        \Closure|string $labelProvider,
        \Closure|null $attributeProvider = null,
    ): self {
        $modelCollection = is_array($models)
            ? Collection::make($models)
            : $models;

        if (is_string($labelProvider)) {
            $labelProvider = static fn ($model) => $model->{$labelProvider};
        }

        $optionCollection = new self(
            $modelCollection->map(static fn ($model) => [
                'label' => $labelProvider($model),
                'value' => $model->getKey(),
            ])
            ->pluck('label', 'value')
            ->toArray()
        );
        $optionCollection->cast = 'int';

        if ($attributeProvider instanceof \Closure) {
            foreach ($modelCollection as $model) {
                $optionCollection->setAttributes($model->getKey(), $attributeProvider($model));
            }
        }

        return $optionCollection;
    }
}
