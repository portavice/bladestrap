<?php

namespace Portavice\Bladestrap\Support;

use ArrayIterator;
use BackedEnum;
use BadMethodCallException;
use Closure;
use Countable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentAttributeBag;
use IteratorAggregate;
use Traversable;

class Options implements Countable, IteratorAggregate
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
        int|string $label,
        int|string $optionValue,
        array|ComponentAttributeBag|null $attributes = null
    ): self {
        $this->options += [$optionValue => $label];
        if ($attributes !== null) {
            $this->setAttributes($optionValue, $attributes);
        }

        return $this;
    }

    public function appendMany(array $labelsByOptionValue, ?Closure $attributeProvider = null): self
    {
        foreach ($labelsByOptionValue as $optionValue => $label) {
            $this->append($label, $optionValue, isset($attributeProvider) ? $attributeProvider($optionValue, $label) : null);
        }

        return $this;
    }

    public function count(): int
    {
        return count($this->options);
    }

    public function getAttributes(int|string $optionValue): ComponentAttributeBag
    {
        return $this->attributesForOption[$optionValue] ?? new ComponentAttributeBag([]);
    }

    public function getCast(): ?string
    {
        return $this->cast;
    }

    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->options);
    }

    public function prepend(
        int|string $label,
        int|string $optionValue,
        array|ComponentAttributeBag|null $attributes = null
    ): self {
        $this->options = [$optionValue => $label] + $this->options;
        if ($attributes !== null) {
            $this->setAttributes($optionValue, $attributes);
        }

        return $this;
    }

    public function prependMany(array $labelsByOptionValue, ?Closure $attributeProvider = null): self
    {
        foreach (array_reverse($labelsByOptionValue, true) as $optionValue => $label) {
            $this->prepend($label, $optionValue, isset($attributeProvider) ? $attributeProvider($optionValue, $label) : null);
        }

        return $this;
    }

    public function setAttributes(int|string $optionValue, array|ComponentAttributeBag $attributes): self
    {
        $this->attributesForOption[$optionValue] = self::wrapAttributes($attributes);

        return $this;
    }

    public function sortAlphabetically(): self
    {
        $array = $this->options;
        asort($array, SORT_NATURAL | SORT_FLAG_CASE);
        $this->options = $array;

        return $this;
    }

    public function sortAlphabeticallyByKeys(): self
    {
        $array = $this->options;
        ksort($array, SORT_NATURAL | SORT_FLAG_CASE);
        $this->options = $array;

        return $this;
    }

    public function sortBy(callable $callback): self
    {
        $this->options = Arr::sort($this->options, $callback);

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
     * @param ?Closure(int|string, int|string): ComponentAttributeBag $attributeProvider
     */
    public static function fromArray(
        array $array,
        ?Closure $attributeProvider = null
    ): self {
        $options = new self($array);

        if ($attributeProvider instanceof Closure) {
            foreach ($array as $optionValue => $label) {
                $options->setAttributes($optionValue, $attributeProvider($optionValue, $label));
            }
        }

        return $options;
    }

    /**
     * @param array<BackedEnum>|class-string<BackedEnum> $enum
     * @param \Closure(<\BackedEnum>): (int|string)|string $labelProvider
     * @param ?\Closure(<\BackedEnum>): ComponentAttributeBag $attributeProvider
     */
    public static function fromEnum(
        array|string $enum,
        Closure|string $labelProvider = 'value',
        ?Closure $attributeProvider = null
    ): self {
        $enumCases = self::enumCases($enum);
        if (is_string($labelProvider)) {
            $labelProvider = match ($labelProvider) {
                'name' => static fn ($enumCase) => $enumCase->name,
                'value' => static fn ($enumCase) => $enumCase->value,
                default => static fn ($enumCase) => $enumCase->{$labelProvider}(),
            };
        }

        $optionArray = [];
        foreach ($enumCases as $enumCase) {
            $optionArray[$enumCase->value] = $labelProvider($enumCase);
        }
        $options = new self($optionArray);
        if (is_int(array_key_first($optionArray))) {
            $options->cast = 'int';
        }

        if ($attributeProvider instanceof Closure) {
            foreach ($enumCases as $enumCase) {
                $options->setAttributes($enumCase->value, $attributeProvider($enumCase));
            }
        }

        return $options;
    }

    /**
     * @param array<BackedEnum>|class-string<BackedEnum> $enum
     */
    private static function enumCases(array|string $enum): array
    {
        if (is_array($enum)) {
            return $enum;
        }

        if (!enum_exists($enum)) {
            throw new BadMethodCallException('Enum expected, but ' . $enum . ' found');
        }

        return $enum::cases();
    }

    /**
     * @param Collection<Model>|Model[] $models
     * @param \Closure(<Model>): (int|string)|string $labelProvider
     * @param ?\Closure(<Model>): ComponentAttributeBag $attributeProvider
     */
    public static function fromModels(
        array|Collection $models,
        Closure|string $labelProvider,
        ?Closure $attributeProvider = null,
    ): self {
        $modelCollection = is_array($models)
            ? Collection::make($models)
            : $models;

        if (is_string($labelProvider)) {
            $labelProvider = static fn ($model) => $model->{$labelProvider};
        }

        $options = new self(
            $modelCollection->map(static fn ($model) => [
                'label' => $labelProvider($model),
                'value' => $model->getKey(),
            ])
            ->pluck('label', 'value')
            ->toArray()
        );
        $options->cast = 'int';

        if ($attributeProvider instanceof Closure) {
            foreach ($modelCollection as $model) {
                $options->setAttributes($model->getKey(), $attributeProvider($model));
            }
        }

        return $options;
    }

    public static function one(
        int|string $label,
        array|ComponentAttributeBag|null $attributes = null
    ): self {
        $options = (new self([]))->prepend($label, 1, $attributes);
        $options->cast = 'int';
        return $options;
    }
}
