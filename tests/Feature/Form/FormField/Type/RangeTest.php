<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsBooleanAttributes;

class RangeTest extends ComponentTestCase
{
    use TestsBooleanAttributes;

    public function testRangeRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
                <label for="my_range" class="form-label">My range</label>
                <input id="my_range" name="my_range" type="range" value="2" class="form-range" min="0" max="10"/>
            </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_range" type="range" :value="$value" min="0" max="10">My range</x-bs::form.field>',
                data: [
                    'value' => 2,
                ]
            )
        );
    }

    /**
     * @dataProvider booleanFormFieldAttributes
     */
    public function testRangeWithBooleanAttributesRendersCorrectly(string $html, string $blade): void
    {
        $expectedHtml = '<div class="mb-3">
                <label for="my_range" class="form-label">My range</label>
                <input id="my_range" name="my_range" type="range" value="2" class="form-range" ' . $html . '/>
            </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_range" type="range" :value="$value" ' . $html . '>My range</x-bs::form.field>',
                data: [
                    'value' => 2,
                ]
            )
        );
    }
}
