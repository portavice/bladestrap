<?php

namespace Feature\Form\FormField;

use Exception;
use Illuminate\View\ViewException;
use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use RuntimeException;

class FormFieldWithInputGroupTest extends ComponentTestCase
{
    #[DataProvider('inputGroups')]
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

    #[DataProvider('inputGroupWithoutContainerForSlot')]
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

    #[DataProvider('nestedFormFields')]
    public function testFormFieldsCanBeNested(string $blade, string $html): void
    {
        $this->assertBladeRendersToHtml($html, $blade);
    }

    public static function nestedFormFields(): array
    {
        return [
            [
                '<x-bs::form.field name="price_from" type="number" min="1" step="1">
                    <x-slot:prependText>from</x-slot:prependText>
                    Price
                    <x-slot:appendText :container="false">
                        <x-bs::form.field name="price_until" type="number" min="1" step="1" :nested-in-group="true">
                            <x-slot:prependText>until</x-slot:prependText>
                            <x-slot:appendText>€</x-slot:appendText>
                        </x-bs::form.field>
                    </x-slot:appendText>
                </x-bs::form.field>',
                '<div class="mb-3">
                    <label for="price_from" class="form-label">Price</label>
                    <div class="input-group">
                        <label for="price_from" class="input-group-text">from</label>
                        <input id="price_from" name="price_from" type="number" class="form-control" min="1" step="1"/>
                        <label for="price_until" class="input-group-text">until</label>
                        <input id="price_until" name="price_until" type="number" class="form-control" min="1" step="1"/>
                        <label for="price_until" class="input-group-text">€</label>
                    </div>
                </div>',
            ],
            [
                '<x-bs::form.field name="amount_from" type="number" min="1" step="1">
                    <x-slot:prependText>from</x-slot:prependText>
                    Amount
                    <x-slot:appendText :container="false">
                        <x-bs::form.field name="amount_until" type="number" min="1" step="1" :nested-in-group="true">
                            <x-slot:prependText>until</x-slot:prependText>
                            <x-slot:appendText :container="false">
                                <x-bs::form.field name="amount_with_step" type="number" min="1" step="1" :nested-in-group="true">
                                    <x-slot:prependText>with step</x-slot:prependText>
                                </x-bs::form.field>
                            </x-slot:appendText>
                        </x-bs::form.field>
                    </x-slot:appendText>
                </x-bs::form.field>',
                '<div class="mb-3">
                    <label for="amount_from" class="form-label">Amount</label>
                    <div class="input-group">
                        <label for="amount_from" class="input-group-text">from</label>
                        <input id="amount_from" name="amount_from" type="number" class="form-control" min="1" step="1"/>
                        <label for="amount_until" class="input-group-text">until</label>
                        <input id="amount_until" name="amount_until" type="number" class="form-control" min="1" step="1"/>
                        <label for="amount_with_step" class="input-group-text">with step</label>
                        <input id="amount_with_step" name="amount_with_step" type="number" class="form-control" min="1" step="1"/>
                    </div>
                </div>',
            ],
        ];
    }

    public function testNestedFormFieldsCannotContainLabel(): void
    {
        $this->expectException(ViewException::class);
        $this->expectExceptionMessage('Attribute nestedInGroup is only allowed with empty slot!');

        try {
            $this->bladeView(
                '<x-bs::form.field name="amount_from" type="number" min="1" step="1">
                    <x-slot:prependText>from</x-slot:prependText>
                    Amount
                    <x-slot:appendText :container="false">
                        <x-bs::form.field name="amount_until" type="number" min="1" step="1" :nested-in-group="true">
                            <x-slot:prependText>until</x-slot:prependText>
                            Some forbidden label
                        </x-bs::form.field>
                    </x-slot:appendText>
                </x-bs::form.field>'
            );
        } catch (Exception $e) {
            $this->assertInstanceOf(RuntimeException::class, $e->getPrevious()->getPrevious());
            throw $e;
        }
    }
}
