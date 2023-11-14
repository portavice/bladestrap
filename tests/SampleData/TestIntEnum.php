<?php

namespace Portavice\Bladestrap\Tests\SampleData;

enum TestIntEnum: int
{
    case Test0 = 0;
    case Test1 = 1;
    case Test2 = 2;

    public function square(): int
    {
        return $this->value * $this->value;
    }
}
