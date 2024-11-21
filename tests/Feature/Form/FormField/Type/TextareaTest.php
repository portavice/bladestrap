<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsBooleanAttributes;

class TextareaTest extends ComponentTestCase
{
    use TestsBooleanAttributes;

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

    #[DataProvider('booleanFormFieldAttributes')]
    public function testTextareaWithBooleanAttributesRendersCorrectly(string $html, string $blade, string $additionalLabel): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="text_block" class="form-label">Text' . $additionalLabel . '</label>
                <textarea id="text_block" name="text_block" class="form-control" rows="5" ' . $html . '></textarea>
            </div>',
            '<x-bs::form.field name="text_block" type="textarea" value="" rows="5" ' . $blade . '>Text</x-bs::form.field>'
        );
    }
}
