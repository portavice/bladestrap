<?php

namespace Portavice\Bladestrap\Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\ComponentAttributeBag;
use PHPUnit\Framework\TestCase;
use Portavice\Bladestrap\Support\OptionCollection;
use Portavice\Bladestrap\Tests\SampleData\TestIntEnum;
use Portavice\Bladestrap\Tests\SampleData\TestModel;
use Portavice\Bladestrap\Tests\SampleData\TestStringEnum;

class OptionCollectionTest extends TestCase
{
    private static array $testIntArray = [
        1 => 'One',
        2 => 'Two',
    ];

    public function testFromArray(): void
    {
        $optionCollection = OptionCollection::fromArray(self::$testIntArray);
        $this->assertEquals(self::$testIntArray, $optionCollection->toArray());
        $this->assertEquals(new ComponentAttributeBag([]), $optionCollection->getAttributes(1));
    }

    public function testFromArrayWithAttributes(): void
    {
        $optionCollection = OptionCollection::fromArray(
            self::$testIntArray,
            static fn ($k, $v) => new ComponentAttributeBag([
                'data-value1' => $k + 2,
                'data-value2' => $k . '_' . $v,
            ])
        );
        $this->assertEquals(self::$testIntArray, $optionCollection->toArray());
        $this->assertEquals(new ComponentAttributeBag([
            'data-value1' => 3,
            'data-value2' => '1_One',
        ]), $optionCollection->getAttributes(1));
    }

    /**
     * @dataProvider enumIntSamples
     */
    public function testFromEnumWithInt(array $expectedOptions, OptionCollection $optionCollection): void
    {
        $this->assertEquals($expectedOptions, $optionCollection->toArray());
        $this->assertEquals('int', $optionCollection->getCast());
    }

    public static function enumIntSamples(): array
    {
        return [
            [[0, 1, 2], OptionCollection::fromEnum(TestIntEnum::class)],
            [['Test0', 'Test1', 'Test2'], OptionCollection::fromEnum(TestIntEnum::class, 'name')],
            [[0, 1, 4], OptionCollection::fromEnum(TestIntEnum::class, 'square')],
            [[1 => 1, 2 => 4], OptionCollection::fromEnum([TestIntEnum::Test1, TestIntEnum::Test2], 'square')],
            [
                [0 => 'Really Test0', 2 => 'Test2'],
                OptionCollection::fromEnum(
                    [TestIntEnum::Test0, TestIntEnum::Test2],
                    static fn (TestIntEnum $case) => match ($case) {
                        TestIntEnum::Test0 => 'Really Test0',
                        default => $case->name,
                    }
                ),
            ],
        ];
    }

    /**
     * @dataProvider enumStringSamples
     */
    public function testFromEnumWithString(array $expectedOptions, OptionCollection $optionCollection): void
    {
        $this->assertEquals($expectedOptions, $optionCollection->toArray());
        $this->assertNull($optionCollection->getCast());
    }

    public function testFromEnumWithModelFails(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Enum expected but '. TestModel::class. ' found');
        OptionCollection::fromEnum(TestModel::class);
    }

    public static function enumStringSamples(): array
    {
        return [
            [['Test1' => 'Test1', 'Test2' => 'Test2'], OptionCollection::fromEnum(TestStringEnum::class)],
            [['Test1' => '1', 'Test2' => '2'], OptionCollection::fromEnum(TestStringEnum::class, 'getSuffix')],
        ];
    }

    /**
     * @dataProvider modelProvider
     */
    public function testFromModels(array|Collection $testModels, array $testModelsIdToName): void
    {
        // With column.
        $optionCollection = OptionCollection::fromModels($testModels, 'name');
        $this->assertEquals($testModelsIdToName, $optionCollection->toArray());

        // With accessor.
        $optionCollection = OptionCollection::fromModels($testModels, 'display_name');
        $this->assertEquals([
            1 => 'Test model (TM)',
            2 => 'Another test model (ATM)',
            4 => 'Yet another test model (YATM)',
        ], $optionCollection->toArray());

        // With \Closure.
        $optionCollection = OptionCollection::fromModels(
            $testModels,
            static fn (TestModel $model) => sprintf('%s (ID %s)', $model->name, $model->id)
        );
        $this->assertEquals([
            1 => 'Test model (ID 1)',
            2 => 'Another test model (ID 2)',
            4 => 'Yet another test model (ID 4)',
        ], $optionCollection->toArray());
    }

    /**
     * @dataProvider modelProvider
     */
    public function testFromModelsWithAttributes(array|Collection $testModels, array $testModelsIdToName): void
    {
        $optionCollection = OptionCollection::fromModels($testModels, 'name', static function (TestModel $model) {
            $attributes = new ComponentAttributeBag([
                'data-short-name' => $model->short_name,
            ]);

            if ($model->id === 1) {
                $attributes = $attributes->merge([
                    'data-is-first' => 'true',
                ]);
            }

            return $attributes;
        });
        $this->assertEquals($testModelsIdToName, $optionCollection->toArray());

        $this->assertEquals(new ComponentAttributeBag([
            'data-short-name' => 'TM',
            'data-is-first' => 'true',
        ]), $optionCollection->getAttributes(1));
        $this->assertEquals(new ComponentAttributeBag([
            'data-short-name' => 'ATM',
        ]), $optionCollection->getAttributes(2));

        $optionCollection = OptionCollection::fromModels($testModels, 'name', static function (TestModel $model) {
            return (new ComponentAttributeBag([]))->class([
                'test',
                'odd' => $model->id % 2 === 1,
            ]);
        });
        $this->assertEquals($testModelsIdToName, $optionCollection->toArray());
        $this->assertEquals(new ComponentAttributeBag([
            'class' => 'test odd',
        ]), $optionCollection->getAttributes(1));
        $this->assertEquals(new ComponentAttributeBag([
            'class' => 'test',
        ]), $optionCollection->getAttributes(2));
    }

    public static function modelProvider(): array
    {
        $testModels = TestModel::samples();
        $testModelsIdToName = [
            1 => 'Test model',
            2 => 'Another test model',
            4 => 'Yet another test model',
        ];

        return [
            [$testModels, $testModelsIdToName],
            [$testModels->all(), $testModelsIdToName],
        ];
    }

    public function testAddAttributes(): void
    {
        $optionCollection = OptionCollection::fromArray(self::$testIntArray);
        $optionCollection->addAttributes(1, ['data-value1' => 42]);
        $optionCollection->addAttributes(1, ['data-value2' => 43]);
        $this->assertEquals([
            'data-value1' => 42,
            'data-value2' => 43,
        ], $optionCollection->getAttributes(1)->getAttributes());
    }

    public function testSetAttributes(): void
    {
        $optionCollection = OptionCollection::fromArray(self::$testIntArray);
        $optionCollection->setAttributes(1, new ComponentAttributeBag(['data-value1' => 42]));
        $optionCollection->setAttributes(1, ['data-value2' => 43]);
        $this->assertEquals([
            'data-value2' => 43,
        ], $optionCollection->getAttributes(1)->getAttributes());
    }
}
