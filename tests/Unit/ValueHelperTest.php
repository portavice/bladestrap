<?php

namespace Portavice\Bladestrap\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Portavice\Bladestrap\Support\ValueHelper;
use Portavice\Bladestrap\Tests\Feature\Form\FormField\FormFieldValuesFilledFromQueryTest;
use Portavice\Bladestrap\Tests\SampleData\TestIntEnum;
use Portavice\Bladestrap\Tests\SampleData\TestModel;
use Portavice\Bladestrap\Tests\SampleData\TestStringEnum;

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

    public function testCastEnum(): void
    {
        $this->assertEquals(2, ValueHelper::castValue(TestIntEnum::Test2, null));
        $this->assertEquals('Test2', ValueHelper::castValue(TestStringEnum::Test2, null));
    }

    public function testCastModel(): void
    {
        $testModel = TestModel::samples()->random(1)->first();
        $this->assertEquals($testModel->id, ValueHelper::castValue($testModel, null));
    }

    public function testCastValueToBool(): void
    {
        $this->assertTrue(ValueHelper::castValue(1, 'bool'));
        $this->assertTrue(ValueHelper::castValue('1', 'bool'));
        $this->assertFalse(ValueHelper::castValue(0, 'bool'));
        $this->assertFalse(ValueHelper::castValue('0', 'bool'));

        $this->assertEquals([true, true], ValueHelper::castValue([1, 1], 'bool'));
        $this->assertEquals([true, false], ValueHelper::castValue(['1', '0'], 'bool'));
    }

    public function testCastValueToInteger(): void
    {
        $this->assertEquals(1, ValueHelper::castValue(1, 'integer'));
        $this->assertEquals(1, ValueHelper::castValue('1', 'integer'));

        $this->assertEquals([1, 42], ValueHelper::castValue([1, 42], 'integer'));
        $this->assertEquals([1, 42], ValueHelper::castValue(['1', '42'], 'integer'));
    }

    public function testCastValueWithClosure(): void
    {
        $cast = static fn ($v) => in_array($v, ['+', '-', '*'], true) ? $v : (int) $v;

        $this->assertEquals('+', ValueHelper::castValue('+', $cast));
        $this->assertEquals(42, ValueHelper::castValue('42', $cast));

        $this->assertEquals(['*', 42], ValueHelper::castValue(['*', 42], $cast));
        $this->assertEquals(['-', 42], ValueHelper::castValue(['-', '42'], $cast));
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
