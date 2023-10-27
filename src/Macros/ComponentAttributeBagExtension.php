<?php

namespace Portavice\Bladestrap\Macros;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\View\ComponentAttributeBag;

class ComponentAttributeBagExtension
{
    public static function registerMacros(): void
    {
        ComponentAttributeBag::macro('filterAndTransform', function (string $prefix) {
            return new ComponentAttributeBag(
                Arr::mapWithKeys(
                    $this->whereStartsWith($prefix)->getAttributes(),
                    static fn ($value, $key) => [Str::after($key, $prefix) => $value]
                )
            );
        });
    }
}
