<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Support\OptionCollection;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsBooleanAttributes;

class RadioTest extends ComponentTestCase
{
    use TestsBooleanAttributes;

    public function testRadioRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
            <label for="my_model" class="form-label">My Model</label>
            <div class="form-check">
                <input id="my_model-1" name="my_model" type="radio" value="1" class="form-check-input"/>
                <label class="form-check-label" for="my_model-1">A</label>
            </div>
            <div class="form-check">
                <input id="my_model-2" name="my_model" type="radio" value="2" class="form-check-input" checked/>
                <label class="form-check-label" for="my_model-2">B</label>
            </div>
            <div class="form-check">
                <input id="my_model-3" name="my_model" type="radio" value="3" class="form-check-input"/>
                <label class="form-check-label" for="my_model-3">C</label>
            </div>
        </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="radio" :options="$options" :value="$value">My Model</x-bs::form.field>',
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
                '<x-bs::form.field name="my_model" type="radio" :options="$options" cast="int" :value="$value">My Model</x-bs::form.field>',
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

    public function testRadioWithAttributesRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
            <label for="my_model" class="form-label">My Model</label>
            <div class="form-check">
                <input id="my_model-0" name="my_model" type="radio" value="0" class="form-check-input text-danger"/>
                <label class="form-check-label" for="my_model-0">No</label>
            </div>
            <div class="form-check">
                <input id="my_model-1" name="my_model" type="radio" value="1" class="form-check-input text-success" checked/>
                <label class="form-check-label" for="my_model-1">Yes</label>
            </div>
        </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="radio" :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => OptionCollection::fromArray(['No', 'Yes'], static fn ($optionValue) => [
                        'class' => match ($optionValue) {
                            0 => 'text-danger',
                            1 => 'text-success',
                        },
                    ]),
                    'value' => 1,
                ]
            )
        );
    }

    /**
     * @dataProvider booleanFormFieldAttributes
     */
    public function testRadioWithBooleanAttributesRendersCorrectly(string $html, string $blade): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <div class="form-check">
                    <input id="my_model-1" name="my_model" type="radio" value="1" class="form-check-input" ' . $html . '/>
                    <label class="form-check-label" for="my_model-1">A</label>
                </div>
                <div class="form-check">
                    <input id="my_model-2" name="my_model" type="radio" value="2" class="form-check-input" checked ' . $html . '/>
                    <label class="form-check-label" for="my_model-2">B</label>
                </div>
                <div class="form-check">
                    <input id="my_model-3" name="my_model" type="radio" value="3" class="form-check-input" ' . $html . '/>
                    <label class="form-check-label" for="my_model-3">C</label>
                </div>
            </div>',
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="radio" :options="$options" :value="$value" ' . $blade . '>My Model</x-bs::form.field>',
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
    }
}
