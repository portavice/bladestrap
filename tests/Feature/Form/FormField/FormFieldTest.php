<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

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

    public function testFormFieldWithoutLabelRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" placeholder="First name"/>
            </div>',
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

    /**
     * @dataProvider booleanFormFieldAttributes
     */
    public function testFormFieldWithBooleanAttributesRendersCorrectly(string $html, string $blade): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="first_name" class="form-label">First name</label>
                <input id="first_name" name="first_name" type="text" value="Patrick" class="form-control" '. $html . '/>
            </div>',
            '<x-bs::form.field name="first_name" type="text" value="Patrick" ' . $blade . '>First name</x-bs::form.field>'
        );
    }
}
