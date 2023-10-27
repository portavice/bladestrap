<?php

namespace Portavice\Bladestrap\Tests\Feature\Form\FormField;

use Illuminate\Http\Request;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormFieldValuesFilledFromOldTest extends ComponentTestCase
{
    /**
     * @dataProvider formDataProvider
     */
    public function testFormFieldFilledWithOldValue(array $old, string $html, string $blade, array $data): void
    {
        $this->mockOld($old);
        $this->assertBladeRendersToHtml($html, $this->bladeView($blade, $data));
    }

    public static function formDataProvider(): array
    {
        return [
            [
                ['test' => 'another-value'],
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <input id="test" name="test" type="text" value="another-value" class="form-control"/>
                </div>',
                '<x-bs::form.field name="test" type="text" value="test-value">Test</x-bs::form.field>',
                [],
            ],
            [
                ['test1' => 'another-value', 'test2' => ''],
                '<div class="mb-3">
                    <label for="test1" class="form-label">Test1</label>
                    <input id="test1" name="test1" type="text" value="another-value" class="form-control"/>
                </div>
                <div class="mb-3">
                    <label for="test2" class="form-label">Test2</label>
                    <input id="test2" name="test2" type="text" value="" class="form-control"/>
                </div>',
                '<x-bs::form.field name="test1" type="text" :value="$value1">Test1</x-bs::form.field>
                <x-bs::form.field name="test2" type="text" :value="$value2">Test2</x-bs::form.field>',
                ['value1' => 'test-value-1', 'value2' => 'test-value-2']
            ],
        ];
    }

    private function mockOld(array $old): void
    {
        $mockRequest = $this->mock(Request::class);
        $mockRequest->allows('setUserResolver')->andReturns($mockRequest);

        /** Answer @see old() calls */
        $mockRequest->allows('old')
            ->andReturnUsing(static function ($key, $default) use ($old) {
                if ($key === null) {
                    return $old;
                }

                return $old[$key] ?? $default ?? null;
            });

        $this->app->instance('request', $mockRequest);
    }
}
