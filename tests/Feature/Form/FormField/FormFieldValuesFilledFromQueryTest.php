<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

use Portavice\Bladestrap\Components\Helpers\ValueHelper;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormFieldValuesFilledFromQueryTest extends ComponentTestCase
{
    public function testFormFieldFilledFromQueryCorrectly(): void
    {
        $this->mockRequest('http://localhost/?test=another-value');
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="test" class="form-label">Test</label>
                <input id="test" name="test" type="text" value="another-value" class="form-control"/>
            </div>',
            $this->bladeView('<x-bs::form.field name="test" type="text" :from-query="true">Test</x-bs::form.field>')
        );
    }

    public function testFormFieldNotFilledFromAnotherQueryParameter(): void
    {
        $this->mockRequest('http://localhost/?test1=another-value&test2=yet-another-value');
        $this->bladeView('<x-bs::form.field name="test" type="text" :from-query="true">Test</x-bs::form.field>')
            ->assertDontSee('another-value')
            ->assertDontSee('yet-another-value');
    }

    public function testFormFieldFilledFromDefaults(): void
    {
        $this->mockRequest('http://localhost');
        ValueHelper::setDefaults([
            'test' => 'default-value',
        ]);

        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="test" class="form-label">Test</label>
                <input id="test" name="test" type="text" value="default-value" class="form-control"/>
            </div>',
            $this->bladeView('<x-bs::form.field name="test" type="text" :from-query="true">Test</x-bs::form.field>')
        );
    }

    public function testHasFromQueryWithDefaultAndWithQueryParameters(): void
    {
        $this->mockRequest('http://localhost?test1=value1&test2=value2');
        ValueHelper::setDefaults([
            'test3' => 'default3',
        ]);

        $this->assertTrue(ValueHelper::hasAnyFromQueryOrDefault());
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test1'));
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test2'));
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test3'));

        $this->assertTrue(ValueHelper::hasAnyFromQuery());
        $this->assertTrue(ValueHelper::hasFromQuery('test1'));
        $this->assertTrue(ValueHelper::hasFromQuery('test2'));
        $this->assertFalse(ValueHelper::hasFromQuery('test3'));

        $this->assertEquals(null, ValueHelper::getDefault('test1'));
        $this->assertEquals(null, ValueHelper::getDefault('test2'));
        $this->assertEquals('default3', ValueHelper::getDefault('test3'));

        $this->assertEquals('value1', ValueHelper::getFromQueryOrDefault('test1'));
        $this->assertEquals('value2', ValueHelper::getFromQueryOrDefault('test2'));
        $this->assertEquals('default3', ValueHelper::getFromQueryOrDefault('test3'));
    }

    public function testHasFromQueryWithDefaultAndWithoutQueryParameters(): void
    {
        $this->mockRequest('http://localhost');
        ValueHelper::setDefaults([
            'test3' => 'default3',
        ]);

        $this->assertTrue(ValueHelper::hasAnyFromQueryOrDefault());
        $this->assertFalse(ValueHelper::hasFromQueryOrDefault('test1'));
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test3'));

        $this->assertFalse(ValueHelper::hasAnyFromQuery());
        $this->assertFalse(ValueHelper::hasFromQuery('test1'));
        $this->assertFalse(ValueHelper::hasFromQuery('test3'));

        $this->assertEquals(null, ValueHelper::getDefault('test1'));
        $this->assertEquals('default3', ValueHelper::getDefault('test3'));

        $this->assertEquals(null, ValueHelper::getFromQueryOrDefault('test1'));
        $this->assertEquals('default3', ValueHelper::getFromQueryOrDefault('test3'));
    }

    public function testHasFromQueryWithoutDefaultAndWithQueryParameters(): void
    {
        $this->mockRequest('http://localhost?test1=value1&test2=value2');
        ValueHelper::setDefaults([]);

        $this->assertTrue(ValueHelper::hasAnyFromQueryOrDefault());
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test1'));
        $this->assertTrue(ValueHelper::hasFromQueryOrDefault('test2'));
        $this->assertFalse(ValueHelper::hasFromQueryOrDefault('test3'));

        $this->assertTrue(ValueHelper::hasAnyFromQuery());
        $this->assertTrue(ValueHelper::hasFromQuery('test1'));
        $this->assertTrue(ValueHelper::hasFromQuery('test2'));
        $this->assertFalse(ValueHelper::hasFromQuery('test3'));

        $this->assertEquals(null, ValueHelper::getDefault('test1'));
        $this->assertEquals(null, ValueHelper::getDefault('test2'));
        $this->assertEquals(null, ValueHelper::getDefault('test3'));

        $this->assertEquals('value1', ValueHelper::getFromQueryOrDefault('test1'));
        $this->assertEquals('value2', ValueHelper::getFromQueryOrDefault('test2'));
        $this->assertEquals(null, ValueHelper::getFromQueryOrDefault('test3'));
    }

    public function testHasFromQueryWithoutDefaultAndWithoutQueryParameters(): void
    {
        $this->mockRequest('http://localhost');
        ValueHelper::setDefaults([]);

        $this->assertFalse(ValueHelper::hasAnyFromQueryOrDefault());
        $this->assertFalse(ValueHelper::hasFromQueryOrDefault('test1'));

        $this->assertFalse(ValueHelper::hasAnyFromQuery());
        $this->assertFalse(ValueHelper::hasFromQuery('test1'));

        $this->assertEquals(null, ValueHelper::getDefault('test1'));
        $this->assertEquals(null, ValueHelper::getFromQueryOrDefault('test1'));
    }
}
