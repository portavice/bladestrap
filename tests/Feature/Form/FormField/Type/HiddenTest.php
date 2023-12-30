<?php

namespace Feature\Form\FormField\Type;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class HiddenTest extends ComponentTestCase
{
    /**
     * @dataProvider hiddenFields
     */
    public function testHiddenInputRendersCorrectly(string $blade): void
    {
        $this->assertBladeRendersToHtml(
            '<input id="my_hidden_field" name="my_hidden_field" type="hidden" value="2"/>',
            $this->bladeView(
                $blade,
                data: [
                    'value' => 2,
                ]
            )
        );
    }

    public static function hiddenFields(): array
    {
        return [
            ['<x-bs::form.field name="my_hidden_field" type="hidden" :value="$value"/>'],
            ['<x-bs::form.field name="my_hidden_field" type="hidden" :value="$value"></x-bs::form.field>'],
            ['<x-bs::form.field name="my_hidden_field" type="hidden" :value="$value">Irrelevant name</x-bs::form.field>'],
        ];
    }
}
