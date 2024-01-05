<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormFieldErrors extends ComponentTestCase
{
    /**
     * @dataProvider allTypes
     */
    public function testFormFieldShowsErrors(string $type, string $output): void
    {
        $outputWithErrorMessage = str_replace(
            'ERROR_PLACEHOLDER',
            '<div role="alert" class="invalid-feedback">
                The test field is required.
            </div>',
            $output
        );
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="test" class="form-label">Test</label>
                ' . $outputWithErrorMessage . '
            </div>',
            $this->bladeView(
                '<x-bs::form.field name="test" ' . $type . ' value="">Test</x-bs::form.field>',
                errors: [
                    'test' => 'The test field is required.',
                ],
            )
        );
    }

    public static function allTypes(): array
    {
        return [
            ...array_map(
                static fn ($value) => [
                    $value[0],
                    $value[1] . "\n" . 'ERROR_PLACEHOLDER',
                ],
                self::types()
            ),
            [
                'type="checkbox" :options="[1 => 1]"',
                '<div class="form-check">
                    <input id="test-1" name="test" type="checkbox" value="1" class="form-check-input is-invalid"/>
                    <label class="form-check-label" for="test-1">1</label>
                    ERROR_PLACEHOLDER
                </div>',
            ],
            ...array_map(
                static fn ($type) => [
                    'type="' . $type . '" :options="[1 => 1, 2 => 2]"',
                    '<div class="form-check">
                        <input id="test-1" name="test" type="' . $type . '" value="1" class="form-check-input is-invalid"/>
                        <label class="form-check-label" for="test-1">1</label>
                    </div>
                    <div class="form-check">
                        <input id="test-2" name="test" type="' . $type . '" value="2" class="form-check-input is-invalid"/>
                        <label class="form-check-label" for="test-2">2</label>
                        ERROR_PLACEHOLDER
                    </div>',
                ],
                ['checkbox', 'radio']
            ),
        ];
    }

    /**
     * @dataProvider types
     */
    public function testFormFieldWithInputGroupShowsErrors(string $type, string $output): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="test" class="form-label">Test</label>
                <div class="input-group has-validation">
                    ' . $output . '
                    <label for="test" class="input-group-text">Append</label>
                    <div role="alert" class="invalid-feedback">
                        The test field is required.
                    </div>
                </div>
            </div>',
            $this->bladeView(
                '<x-bs::form.field name="test" ' . $type . ' value="">
                    Test
                    <x-slot:appendText>Append</x-slot:appendText>
                </x-bs::form.field>',
                errors: [
                    'test' => 'The test field is required.',
                ],
            )
        );
    }

    public static function types(): array
    {
        return [
            [
                'type="range"',
                '<input id="test" name="test" type="range" value="" class="form-range is-invalid"/>',
            ],
            [
                'type="select" :options="[1 => 1, 2 => 2]"',
                '<select id="test" name="test" class="form-select is-invalid">
                    <option value="1">1</option>
                    <option value="2">2</option>
                </select>',
            ],
            [
                'type="text"',
                '<input id="test" name="test" type="text" value="" class="form-control is-invalid"/>',
            ],
            [
                'type="textarea"',
                '<textarea id="test" name="test" class="form-control is-invalid"></textarea>',
            ],
        ];
    }
}
