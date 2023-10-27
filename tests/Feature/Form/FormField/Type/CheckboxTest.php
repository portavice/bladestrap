<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class CheckboxTest extends ComponentTestCase
{
    public function testMultipleCheckboxRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
            <label for="my_model" class="form-label">My Model</label>
            <div class="form-check">
                <input id="my_model-1" name="my_models[]" type="checkbox" value="1" class="form-check-input"/>
                <label class="form-check-label" for="my_model-1">A</label>
            </div>
            <div class="form-check">
                <input id="my_model-2" name="my_models[]" type="checkbox" value="2" class="form-check-input" checked/>
                <label class="form-check-label" for="my_model-2">B</label>
            </div>
            <div class="form-check">
                <input id="my_model-3" name="my_models[]" type="checkbox" value="3" class="form-check-input" checked/>
                <label class="form-check-label" for="my_model-3">C</label>
            </div>
        </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field id="my_model" name="my_models[]" type="checkbox" :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    'value' => [2, 3],
                ]
            )
        );
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field id="my_model" name="my_models[]" type="checkbox" :options="$options" :value="$value" cast="int">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    // '2', '3' need int cast.
                    'value' => ['2', '3'],
                ]
            )
        );
    }

    public function testSimpleCheckboxRendersCorrectly(): void
    {
        $expectedHtml = fn ($additonalHtml) => '<div class="mb-3">
            <label for="setting_enabled" class="form-label">Setting</label>
            <div class="form-check">
                <input id="setting_enabled-1" name="setting_enabled" type="checkbox" value="1" class="form-check-input"' . $additonalHtml . '/>
            <label class="form-check-label" for="setting_enabled-1">Option enabled</label>
            </div>
        </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml(' checked'),
            $this->bladeView(
                '<x-bs::form.field name="setting_enabled" type="checkbox" :options="$options" :value="$value">Setting</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'Option enabled',
                    ],
                    'value' => 1,
                ]
            )
        );
        $this->assertBladeRendersToHtml(
            $expectedHtml(''),
            $this->bladeView(
                '<x-bs::form.field name="setting_enabled" type="checkbox" :options="$options" :value="$value">Setting</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'Option enabled',
                    ],
                    'value' => 0,
                ]
            )
        );
    }
}
