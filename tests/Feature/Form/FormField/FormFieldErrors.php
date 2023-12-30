<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormFieldErrors extends ComponentTestCase
{
    public function testFormFieldShowsErrors(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="test" class="form-label">Test</label>
                <input id="test" name="test" type="text" value="" class="form-control is-invalid"/>
                <div role="alert" class="invalid-feedback">
                    The test field is required.
                </div>
            </div>',
            $this->bladeView(
                '<x-bs::form.field name="test" type="text" value="">Test</x-bs::form.field>',
                errors: [
                    'test' => 'The test field is required.',
                ],
            )
        );
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
