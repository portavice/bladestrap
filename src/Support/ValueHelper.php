<?php

namespace Portavice\Bladestrap\Support;

use Illuminate\Support\Arr;

class ValueHelper
{
    private static array $defaults = [];

    public static function getDefault(string $key): mixed
    {
        return self::$defaults[$key] ?? null;
    }

    public static function hasAnyDefault(): bool
    {
        return count(self::$defaults) > 0;
    }

    public static function hasDefault(string $key): bool
    {
        return array_key_exists($key, self::$defaults);
    }

    public static function setDefaults(array $defaults): void
    {
        self::$defaults = $defaults;
    }

    public static function getFromQueryOrDefault(string $key): mixed
    {
        /** @var \Illuminate\Http\Request $request */
        $request = app('request');

        return $request->query($key) ?? self::$defaults[$key] ?? null;
    }

    public static function hasAnyFromQuery(): bool
    {
        /** @var \Illuminate\Http\Request $request */
        $request = app('request');
        return count($request->query()) > 0;
    }

    public static function hasFromQuery(string $key): bool
    {
        /** @var \Illuminate\Http\Request $request */
        $request = app('request');
        return $request->query($key) !== null;
    }

    public static function hasAnyFromQueryOrDefault(): bool
    {
        return self::hasAnyDefault()
            || self::hasAnyFromQuery();
    }

    public static function hasFromQueryOrDefault(string $key): bool
    {
        return self::hasDefault($key)
            || self::hasFromQuery($key);
    }

    public static function value(string $name, mixed $value, bool $fromQuery, ?string $cast): mixed
    {
        $dotSyntax = self::nameToDotSyntax($name);
        if ($fromQuery) {
            $request = app('request');
            $value = Arr::get(
                $request->query(),
                $dotSyntax,
                Arr::get(self::$defaults, $dotSyntax)
            );
        }

        if (count(old(null, [])) > 0) {
            $value = old(self::nameToDotSyntax($name), $value);
        }

        return self::castValue($value, $cast);
    }

    public static function castValue(mixed $value, ?string $cast): mixed
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value)) {
            return array_map(
                static fn ($valueItem) => self::castValue($valueItem, $cast),
                $value
            );
        }

        return match ($cast) {
            'bool' => (bool) $value,
            'int', 'integer' => (int) $value,
            default => $value,
        };
    }

    /**
     * Converts the name of an HTML form field (e.g. items[data][]) to Laravel dot syntax ('items.data').
     */
    public static function nameToDotSyntax(string $fieldName): string
    {
        return str_replace(
            ['[]', '[', ']'],
            ['', '.', '',],
            $fieldName
        );
    }

    /**
     * Checks whether the value is the selected one.
     */
    public static function isActive(int|string $optionValue, array|int|string|null $selectedValue): bool
    {
        return is_array($selectedValue)
            ? in_array($optionValue, $selectedValue, true)
            : $optionValue === $selectedValue;
    }
}
