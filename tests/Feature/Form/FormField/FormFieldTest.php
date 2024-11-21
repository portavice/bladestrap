<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

use Illuminate\Support\Facades\Config;
use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsBooleanAttributes;

class FormFieldTest extends ComponentTestCase
{
    use TestsBooleanAttributes;

    public function testFormFieldRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="first_name" class="form-label">First name</label>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control"/>
            </div>',
            '<x-bs::form.field name="first_name" type="text" value="Patrick">First name</x-bs::form.field>'
        );
    }

    public function testFormFieldWithHintAndWithoutLabelRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" aria-describedby="first_name-hint"/>
                <div id="first_name-hint" class="form-text">My hint</div>
            </div>',
            '<x-bs::form.field name="first_name" type="text" value="Patrick">
                <x-slot:hint>My hint</x-slot:hint>
            </x-bs::form.field>'
        );
    }

    public function testFormFieldWithoutLabelRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" placeholder="First name"/>',
            '<x-bs::form.field name="first_name" placeholder="First name" type="text" value="Patrick"/>'
        );
    }

    public function testFormFieldWithLabelClassesRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="first_name" class="form-label text-primary">First name</label>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control text-danger"/>
            </div>',
            '<x-bs::form.field label-class="text-primary" name="first_name" type="text" value="Patrick" class="text-danger">First name</x-bs::form.field>'
        );
    }

    public function testNumberRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="count" class="form-label">Count</label>
                <input id="count" name="count" type="number" class="form-control" min="1" max="5" step="0.01"/>
            </div>',
            '<x-bs::form.field name="count" type="number" min="1" max="5" step="0.01">Count</x-bs::form.field>'
        );
    }

    #[DataProvider('booleanFormFieldAttributes')]
    public function testFormFieldWithBooleanAttributesRendersCorrectly(string $html, string $blade, string $additionalLabel): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="first_name" class="form-label">First name' . $additionalLabel . '</label>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" '. $html . '/>
            </div>',
            '<x-bs::form.field name="first_name" type="text" value="Patrick" ' . $blade . '>First name</x-bs::form.field>'
        );
    }

    #[DataProvider('conditionsForRequiredMarker')]
    public function testRequiredFormFieldOnlyMarkedAsRequiredIfEnabled(string $html, string $blade): void
    {
        Config::set('bladestrap.form.field.mark_as_required', false);
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="first_name" class="form-label">First name' . $html . '</label>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" required/>
            </div>',
            $blade
        );
    }

    public static function conditionsForRequiredMarker(): array
    {
        return [
            [
                '',
                '<x-bs::form.field name="first_name" type="text" value="Patrick" :required="true">First name</x-bs::form.field>',
            ],
            [
                ' *',
                '<x-bs::form.field name="first_name" type="text" value="Patrick" :required="true" :mark-as-required="true">First name</x-bs::form.field>',
            ],
        ];
    }
}
