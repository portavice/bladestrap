<?php

namespace Portavice\Bladestrap\Tests\Unit;

use Illuminate\View\ComponentAttributeBag;
use Override;
use PHPUnit\Framework\TestCase;
use Portavice\Bladestrap\Macros\ComponentAttributeBagExtension;

class ComponentAttributeBagMacroTest extends TestCase
{
    #[Override]
    public static function setUpBeforeClass(): void
    {
        ComponentAttributeBagExtension::registerMacros();
    }

    public function testFilterAndTransform(): void
    {
        $attributes = $this->makeBag([
            'container-id' => 'my-id',
            'container-class' => 'my-class',
            'label-class' => 'my-label-class',
            'class' => 'another-class',
            'type' => 'number',
            'min' => 1,
            'max' => 5,
        ]);
        $this->assertEquals(
            $this->makeBag([
                'id' => 'my-id',
                'class' => 'my-class',
            ]),
            $attributes->filterAndTransform('container-')
        );
        $this->assertEquals(
            $this->makeBag([
                'class' => 'my-label-class',
            ]),
            $attributes->filterAndTransform('label-')
        );
    }

    private function makeBag(array $attributes): ComponentAttributeBag
    {
        return new ComponentAttributeBag($attributes);
    }
}
