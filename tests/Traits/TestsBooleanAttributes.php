<?php

namespace Portavice\Bladestrap\Tests\Traits;

trait TestsBooleanAttributes
{
    public static function booleanFormFieldAttributes(): array
    {
        return [
            ['disabled', ':disabled="true"', ''],
            ['disabled readonly', ':disabled="true" :readonly="true"', ''],
            ['disabled readonly', ':readonly="true" :disabled="true"', ''],
            ['readonly', ':readonly="true"', ''],
            ['required', ':required="true"', ' *'],
            ['required', ':required="true" :mark-as-required="false"', ''],
        ];
    }
}
