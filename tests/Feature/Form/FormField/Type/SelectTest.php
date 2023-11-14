<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField\Type;

use Illuminate\View\ComponentAttributeBag;
use Portavice\Bladestrap\Support\OptionCollection;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\SampleData\TestModel;

class SelectTest extends ComponentTestCase
{
    public function testSelectRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <select id="my_model" name="my_model" class="form-select">
                    <option value="1">A</option>
                    <option value="2" selected>B</option>
                    <option value="3">C</option>
                </select>
            </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    'value' => 2,
                ]
            )
        );
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" :options="$options" :value="$value" cast="int">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    // '2' needs int cast.
                    'value' => '2',
                ]
            )
        );
    }

    public function testMultipleSelectRendersCorrectly(): void
    {
        $expectedHtml = '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <select id="my_model" name="my_model" class="form-select" multiple="multiple">
                    <option value="1">A</option>
                    <option value="2" selected>B</option>
                    <option value="3" selected>C</option>
                </select>
            </div>';
        $this->assertBladeRendersToHtml(
            $expectedHtml,
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" multiple :options="$options" :value="$value">My Model</x-bs::form.field>',
                data: [
                    'options' => [
                        1 => 'A',
                        2 => 'B',
                        3 => 'C',
                    ],
                    'value' => [2, 3],
                ]
            )
        );
    }

    /**
     * @dataProvider attributesProvider
     */
    public function testSelectWithOptionCollectionRendersCorrectly(OptionCollection $optionCollection, ?string $cast, string $html): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="mb-3">
                <label for="my_model" class="form-label">My Model</label>
                <select id="my_model" name="my_model" class="form-select">
                    ' . $html . '
                </select>
            </div>',
            $this->bladeView(
                '<x-bs::form.field name="my_model" type="select" :options="$options" :value="$value"'
                . (isset($cast) ? sprintf(' cast="%s"', $cast) : '')
                . '>My Model</x-bs::form.field>',
                data: [
                    'options' => $optionCollection,
                    'value' => 2,
                ]
            )
        );
    }

    public static function attributesProvider(): array
    {
        $defaultHtml = '<option value="1">TM</option>
                <option value="2" selected>ATM</option>
                <option value="4">YATM</option>';
        $htmlWithAttributes = '<option value="1" class="test" data-short-name="TM">Test model</option>
                <option value="2" class="test even" data-short-name="ATM" selected>Another test model</option>
                <option value="4" class="test even" data-short-name="YATM">Yet another test model</option>';
        return [
            [
                OptionCollection::fromModels(TestModel::samples(), 'short_name'),
                'int',
                $defaultHtml,
            ],
            [
                OptionCollection::fromModels(TestModel::samples(), static fn (TestModel $model) => $model->short_name),
                'int',
                $defaultHtml,
            ],
            [
                OptionCollection::fromModels(TestModel::samples(), static fn (TestModel $model) => $model->short_name),
                null, // int will be set by OptionCollection::makeModels.
                $defaultHtml,
            ],
            [
                OptionCollection::fromModels(TestModel::samples(), 'name', static function (TestModel $model) {
                    return (new ComponentAttributeBag([
                        'data-short-name' => $model->short_name,
                    ]))->class([
                        'test',
                        'even' => $model->id % 2 === 0,
                    ]);
                }),
                null,
                $htmlWithAttributes,
            ],
            [
                OptionCollection::fromModels(TestModel::samples(), 'name', fn (TestModel $model) => [
                    'class' => 'test' . ($model->id % 2 === 0 ? ' even' : ''),
                    'data-short-name' => $model->short_name,
                ]),
                null,
                $htmlWithAttributes,
            ],
        ];
    }
}
