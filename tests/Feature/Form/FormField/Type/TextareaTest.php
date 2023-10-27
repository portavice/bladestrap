<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class TextareaTest extends ComponentTestCase
{
    public function testTextareaRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="text_block" class="form-label">Text</label>
                <textarea id="text_block" name="text_block" class="form-control" rows="5">DefaultText</textarea>
            </div>',
            '<x-bs::form.field name="text_block" type="textarea" value="DefaultText" rows="5">Text</x-bs::form.field>'
        );
    }

    public function testRequiredTextareaRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="text_block" class="form-label">Text</label>
                <textarea id="text_block" name="text_block" class="form-control" rows="5" required></textarea>
            </div>',
            '<x-bs::form.field name="text_block" type="textarea" value="" rows="5" :required="true">Text</x-bs::form.field>'
        );
    }

    public function testDisabledTextareaRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="text_block" class="form-label">Text</label>
                <textarea id="text_block" name="text_block" class="form-control" disabled>DefaultText</textarea>
            </div>',
            '<x-bs::form.field name="text_block" type="textarea" value="DefaultText" :disabled="true">Text</x-bs::form.field>'
        );
    }
}
