<?php

namespace Portavice\Bladestrap\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Portavice\Bladestrap\Support\ValueHelper;
use Portavice\Bladestrap\Tests\Feature\Form\FormField\FormFieldValuesFilledFromQueryTest;

class ValueHelperTest extends TestCase
{
    public function testNameToDotStyle(): void
    {
        $this->assertEquals('filter.name', ValueHelper::nameToDotSyntax('filter[name]'));
        $this->assertEquals('name', ValueHelper::nameToDotSyntax('name'));
        $this->assertEquals('names', ValueHelper::nameToDotSyntax('names[]'));
        $this->assertEquals('names.1', ValueHelper::nameToDotSyntax('names[1]'));
        $this->assertEquals('names.1', ValueHelper::nameToDotSyntax('names[1][]'));
    }

    public function testCastValue(): void
    {
        $this->assertEquals(1, ValueHelper::castValue(1, 'integer'));
        $this->assertEquals(1, ValueHelper::castValue('1', 'integer'));

        $this->assertTrue(ValueHelper::castValue(1, 'bool'));
        $this->assertTrue(ValueHelper::castValue('1', 'bool'));
        $this->assertFalse(ValueHelper::castValue(0, 'bool'));
        $this->assertFalse(ValueHelper::castValue('0', 'bool'));
    }

    public function testHasDefault(): void
    {
        ValueHelper::setDefaults([]);
        $this->assertFalse(ValueHelper::hasAnyDefault());

        ValueHelper::setDefaults([
            'test1' => 'default1',
            'test2' => 'default2',
        ]);
        $this->assertTrue(ValueHelper::hasAnyDefault());
        $this->assertTrue(ValueHelper::hasAnyFromQueryOrDefault());

        $this->assertTrue(ValueHelper::hasDefault('test1'));
        $this->assertTrue(ValueHelper::hasDefault('test2'));
        $this->assertFalse(ValueHelper::hasDefault('test3'));

        $this->assertEquals('default1', ValueHelper::getDefault('test1'));
        $this->assertEquals('default2', ValueHelper::getDefault('test2'));
        $this->assertEquals(null, ValueHelper::getDefault('test3'));
    }

    /**
     * @see FormFieldValuesFilledFromQueryTest::testHasFromQueryWithDefaultAndWithQueryParameters()
     * for more tests which require a request.
     */
}
