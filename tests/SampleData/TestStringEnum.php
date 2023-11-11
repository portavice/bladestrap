<?php

namespace Portavice\Bladestrap\Tests\SampleData;

enum TestStringEnum: string
{
    case Test1 = 'Test1';
    case Test2 = 'Test2';

    public function getSuffix(): string
    {
        return str_replace('Test', '', $this->value);
    }
}
