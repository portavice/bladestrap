<?php

namespace Feature\Form\FormField;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormFieldWithInputGroupTest extends ComponentTestCase
{
    /**
     * @dataProvider inputGroups
     */
    public function testFieldWithInputGroupFromTextCorrectly(string $slots, string $append, string $prepend): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <div class="input-group">
                    ' . $append
                    . '<input id="price" name="price" type="number" class="form-control" min="0.01" step="0.01"/>'
                    . $prepend . '
                </div>
            </div>',
            '<x-bs::form.field name="price" type="number" min="0.01" step="0.01">
                Price
                ' . $slots . '
            </x-bs::form.field>'
        );
    }

    public static function inputGroups(): array
    {
        return [
            [
                '<x-slot:prependText>≥</x-slot>',
                '<label for="price" class="input-group-text">≥</label>' . "\n",
                '',
            ],
            [
                '<x-slot:appendText>€</x-slot>',
                '',
                "\n" .'<label for="price" class="input-group-text">€</label>',
            ],
            [
                '<x-slot:prependText>≥</x-slot>
                <x-slot:appendText>€</x-slot>',
                '<label for="price" class="input-group-text">≥</label>' . "\n",
                "\n" .'<label for="price" class="input-group-text">€</label>',
            ],
        ];
    }

    /**
     * @dataProvider inputGroupWithoutContainerForSlot
     */
    public function testFieldWithInputGroupContainer(string $slot, string $append, string $prepend): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="file" class="form-label">File</label>
                <div class="input-group">
                    ' . $append
                    . '<input id="file" name="file" type="file" class="form-control"/>'
                    . $prepend . '
                </div>
            </div>',
            '<x-bs::form.field name="file" type="file">
                File
                <x-slot ' . $slot . '>
                    <x-bs::button.link variant="primary" href="test.pdf">Download current file</x-bs::button.link>
                </x-slot>
            </x-bs::form.field>'
        );
    }

    public static function inputGroupWithoutContainerForSlot(): array
    {
        return [
            [
                'name="prependText" :container="false"',
                '<a class="btn btn-primary" href="test.pdf">Download current file</a>' . "\n",
                '',
            ],
            [
                'name="appendText" :container="false"',
                '',
                "\n" . '<a class="btn btn-primary" href="test.pdf">Download current file</a>',
            ],
        ];
    }
}
