<?php

namespace Portavice\Bladestrap\Tests\Unit;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\ComponentAttributeBag;
use PHPUnit\Framework\TestCase;
use Portavice\Bladestrap\Support\Options;
use Portavice\Bladestrap\Tests\SampleData\TestIntEnum;
use Portavice\Bladestrap\Tests\SampleData\TestModel;
use Portavice\Bladestrap\Tests\SampleData\TestStringEnum;

class OptionsTest extends TestCase
{
    private static array $testIntArray = [
        1 => 'One',
        2 => 'Two',
    ];

    public function testFromArray(): void
    {
        $options = Options::fromArray(self::$testIntArray);
        $this->assertEquals(self::$testIntArray, $options->toArray());
        $this->assertEquals(new ComponentAttributeBag([]), $options->getAttributes(1));
    }

    public function testFromArrayWithAttributes(): void
    {
        $options = Options::fromArray(
            self::$testIntArray,
            static fn ($k, $v) => new ComponentAttributeBag([
                'data-value1' => $k + 2,
                'data-value2' => $k . '_' . $v,
            ])
        );
        $this->assertEquals(self::$testIntArray, $options->toArray());
        $this->assertEquals(new ComponentAttributeBag([
            'data-value1' => 3,
            'data-value2' => '1_One',
        ]), $options->getAttributes(1));
    }

    /**
     * @dataProvider enumIntSamples
     */
    public function testFromEnumWithInt(array $expectedOptions, Options $options): void
    {
        $this->assertEquals($expectedOptions, $options->toArray());
        $this->assertEquals('int', $options->getCast());
    }

    public static function enumIntSamples(): array
    {
        return [
            [[0, 1, 2], Options::fromEnum(TestIntEnum::class)],
            [['Test0', 'Test1', 'Test2'], Options::fromEnum(TestIntEnum::class, 'name')],
            [[0, 1, 4], Options::fromEnum(TestIntEnum::class, 'square')],
            [[1 => 1, 2 => 4], Options::fromEnum([TestIntEnum::Test1, TestIntEnum::Test2], 'square')],
            [
                [0 => 'Really Test0', 2 => 'Test2'],
                Options::fromEnum(
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
    public function testFromEnumWithString(array $expectedOptions, Options $options): void
    {
        $this->assertEquals($expectedOptions, $options->toArray());
        $this->assertNull($options->getCast());
    }

    public function testFromEnumWithModelFails(): void
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Enum expected, but '. TestModel::class. ' found');
        Options::fromEnum(TestModel::class);
    }

    public static function enumStringSamples(): array
    {
        return [
            [['Test1' => 'Test1', 'Test2' => 'Test2'], Options::fromEnum(TestStringEnum::class)],
            [['Test1' => '1', 'Test2' => '2'], Options::fromEnum(TestStringEnum::class, 'getSuffix')],
        ];
    }

    /**
     * @dataProvider modelProvider
     */
    public function testFromModels(array|Collection $testModels, array $testModelsIdToName): void
    {
        // With column.
        $options = Options::fromModels($testModels, 'name');
        $this->assertEquals($testModelsIdToName, $options->toArray());

        // With accessor.
        $options = Options::fromModels($testModels, 'display_name');
        $this->assertEquals([
            1 => 'Test model (TM)',
            2 => 'Another test model (ATM)',
            4 => 'Yet another test model (YATM)',
        ], $options->toArray());

        // With \Closure.
        $options = Options::fromModels(
            $testModels,
            static fn (TestModel $model) => sprintf('%s (ID %s)', $model->name, $model->id)
        );
        $this->assertEquals([
            1 => 'Test model (ID 1)',
            2 => 'Another test model (ID 2)',
            4 => 'Yet another test model (ID 4)',
        ], $options->toArray());
    }

    /**
     * @dataProvider modelProvider
     */
    public function testFromModelsWithAttributes(array|Collection $testModels, array $testModelsIdToName): void
    {
        $options = Options::fromModels($testModels, 'name', static function (TestModel $model) {
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
        $this->assertEquals($testModelsIdToName, $options->toArray());

        $this->assertEquals(new ComponentAttributeBag([
            'data-short-name' => 'TM',
            'data-is-first' => 'true',
        ]), $options->getAttributes(1));
        $this->assertEquals(new ComponentAttributeBag([
            'data-short-name' => 'ATM',
        ]), $options->getAttributes(2));

        $options = Options::fromModels($testModels, 'name', static function (TestModel $model) {
            return (new ComponentAttributeBag([]))->class([
                'test',
                'odd' => $model->id % 2 === 1,
            ]);
        });
        $this->assertEquals($testModelsIdToName, $options->toArray());
        $this->assertEquals(new ComponentAttributeBag([
            'class' => 'test odd',
        ]), $options->getAttributes(1));
        $this->assertEquals(new ComponentAttributeBag([
            'class' => 'test',
        ]), $options->getAttributes(2));
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
        $options = Options::fromArray(self::$testIntArray);
        $options->addAttributes(1, ['data-value1' => 42]);
        $options->addAttributes(1, ['data-value2' => 43]);
        $this->assertEquals([
            'data-value1' => 42,
            'data-value2' => 43,
        ], $options->getAttributes(1)->getAttributes());
    }

    public function testAppendAttributes(): void
    {
        $options = Options::fromArray(self::$testIntArray);
        $options->append('all', '');

        $this->assertEquals([
            1 => 'One',
            2 => 'Two',
            '' => 'all',
        ], $options->toArray());

        $options->append('Three', 3, ['class' => 'test']);
        $this->assertEquals([
            '' => 'all',
            1 => 'One',
            2 => 'Two',
            3 => 'Three',
        ], $options->toArray());
        $this->assertEquals(new ComponentAttributeBag(['class' => 'test']), $options->getAttributes(3));
    }

    public function testPrependAttributes(): void
    {
        $options = Options::fromArray(self::$testIntArray)
            ->prepend('all', '');

        $this->assertEquals([
            '' => 'all',
            1 => 'One',
            2 => 'Two',
        ], $options->toArray());

        $options->prepend('Three', 3, ['class' => 'test']);
        $this->assertEquals([
            3 => 'Three',
            '' => 'all',
            1 => 'One',
            2 => 'Two',
        ], $options->toArray());
        $this->assertEquals(new ComponentAttributeBag(['class' => 'test']), $options->getAttributes(3));
    }

    public function testSetAttributes(): void
    {
        $options = Options::fromArray(self::$testIntArray)
            ->setAttributes(1, new ComponentAttributeBag(['data-value1' => 42]))
            ->setAttributes(1, ['data-value2' => 43]);
        $this->assertEquals([
            'data-value2' => 43,
        ], $options->getAttributes(1)->getAttributes());
    }
}
