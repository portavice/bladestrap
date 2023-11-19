<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Support\OptionCollection;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsBooleanAttributes;

class CheckboxTest extends ComponentTestCase
{
    use TestsBooleanAttributes;

    /**
     * @dataProvider checkBoxTypes
     */
    public function testMultipleCheckboxRendersCorrectly(
        string $componentType,
        string $checkClass,
        string $additionalAttributes
    ): void {
        $expectedHtml = '<div class="mb-3">
            <label for="my_model" class="form-label">My Model</label>
            <div class="CHECK_CLASS_PLACEHOLDER">
                <input id="my_model-1" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="1" class="form-check-input"/>
                <label class="form-check-label" for="my_model-1">A</label>
            </div>
            <div class="CHECK_CLASS_PLACEHOLDER">
                <input id="my_model-2" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="2" class="form-check-input" checked/>
                <label class="form-check-label" for="my_model-2">B</label>
            </div>
            <div class="CHECK_CLASS_PLACEHOLDER">
                <input id="my_model-3" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="3" class="form-check-input" checked/>
                <label class="form-check-label" for="my_model-3">C</label>
            </div>
        </div>';
        $expectedHtml = self::makeExpectedHtml(
            $expectedHtml,
            $checkClass,
            $additionalAttributes
        );
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field id="my_model" name="my_models[]" type="' . $componentType .'"
                                         :options="$options" :value="$value">My Model</x-bs::form.field>',
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
                '<x-bs::form.field id="my_model" name="my_models[]" type="' . $componentType .'"
                                         :options="$options" :value="$value" cast="int">My Model</x-bs::form.field>',
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

    /**
     * @dataProvider checkBoxTypes
     */
    public function testCheckboxWithAttributesRendersCorrectly(
        string $componentType,
        string $checkClass,
        string $additionalAttributes
    ): void {
        $expectedHtml = '<div class="mb-3">
            <label for="my_model" class="form-label">My Model</label>
            <div class="CHECK_CLASS_PLACEHOLDER test-check-A" data-value="test-1">
                <input id="my_model-1" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="1" class="form-check-input" data-value="test-1"/>
                <label class="form-check-label" for="my_model-1">A</label>
            </div>
            <div class="CHECK_CLASS_PLACEHOLDER test-check-B" data-value="test-2">
                <input id="my_model-2" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="2" class="form-check-input" data-value="test-2" checked/>
                <label class="form-check-label" for="my_model-2">B</label>
            </div>
            <div class="CHECK_CLASS_PLACEHOLDER test-check-C" data-value="test-3">
                <input id="my_model-3" name="my_models[]" ATTRIBUTES_PLACEHOLDER type="checkbox" value="3" class="form-check-input" data-value="test-3" checked/>
                <label class="form-check-label" for="my_model-3">C</label>
            </div>
        </div>';

        $options = OptionCollection::fromArray([
            1 => 'A',
            2 => 'B',
            3 => 'C',
        ], static fn ($optionValue, $label) => [
            'check-class' => 'test-check-' . $label,
            'check-data-value' => 'test-' . $optionValue,
            'data-value' => 'test-' . $optionValue,
        ]);
        $this->assertBladeRendersToHtml(
            self::makeExpectedHtml($expectedHtml, $checkClass, $additionalAttributes),
            $this->bladeView(
                '<x-bs::form.field id="my_model" name="my_models[]" type="' . $componentType .'"
                                         :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => $options,
                    'value' => [2, 3],
                ]
            )
        );
    }

    public static function checkBoxTypes(): array
    {
        return [
            ['checkbox', 'form-check', ''],
            ['switch', 'form-check form-switch', 'role="switch"'],
        ];
    }

    private static function makeExpectedHtml(
        string $html,
        string $checkClass,
        string $additionalAttributes
    ): string {
        return str_replace(
            ['CHECK_CLASS_PLACEHOLDER', 'ATTRIBUTES_PLACEHOLDER'],
            [$checkClass, $additionalAttributes],
            $html
        );
    }

    public function testSimpleCheckboxRendersCorrectly(): void
    {
        $expectedHtml = static fn ($additionalHtml) => '<div class="mb-3">
            <label for="setting_enabled" class="form-label">Setting</label>
            <div class="form-check">
                <input id="setting_enabled-1" name="setting_enabled" type="checkbox" value="1" class="form-check-input"' . $additionalHtml . '/>
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

    /**
     * @dataProvider booleanFormFieldAttributes
     */
    public function testFormFieldWithBooleanAttributesRendersCorrectly(string $html, string $blade): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <div class="form-check">
                    <input id="my_model-1" name="my_models[]" type="checkbox" value="1" class="form-check-input" ' . $html . '/>
                    <label class="form-check-label" for="my_model-1">A</label>
                </div>
                <div class="form-check">
                    <input id="my_model-2" name="my_models[]" type="checkbox" value="2" class="form-check-input" checked ' . $html . '/>
                    <label class="form-check-label" for="my_model-2">B</label>
                </div>
                <div class="form-check">
                    <input id="my_model-3" name="my_models[]" type="checkbox" value="3" class="form-check-input" checked ' . $html . '/>
                    <label class="form-check-label" for="my_model-3">C</label>
                </div>
            </div>',
            $this->bladeView(
                '<x-bs::form.field id="my_model" name="my_models[]" type="checkbox"
                                         :options="$options" :value="$value" cast="int" ' . $blade . '>My Model</x-bs::form.field>',
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
    }
}
