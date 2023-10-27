<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class SelectTest extends ComponentTestCase
{
    public function testSelectRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <select id="my_model" name="my_model" class="form-select">
                    <option value="1">A</option>
                    <option value="2" selected>B</option>
                    <option value="3">C</option>
                </select>
            </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    'value' => 2,
                ]
            )
        );
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" :options="$options" :value="$value" cast="int">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    // '2' needs int cast.
                    'value' => '2',
                ]
            )
        );
    }
}
