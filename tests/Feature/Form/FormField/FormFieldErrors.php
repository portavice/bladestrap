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
}
