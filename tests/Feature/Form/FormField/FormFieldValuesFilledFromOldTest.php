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
            // type checkbox
            [
                ['test' => '1'], // old value: selected
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <div class="form-check">
                        <input id="test-1" name="test" type="checkbox" value="1" class="form-check-input" checked/>
                        <label class="form-check-label" for="test-1">1</label>
                    </div>
                </div>',
                '<x-bs::form.field name="test" type="checkbox" :options="\Portavice\Bladestrap\Support\Options::one(1)" value="0">Test</x-bs::form.field>',
                [],
            ],
            [
                ['test2' => 1], // no old values for test
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <div class="form-check">
                        <input id="test-1" name="test" type="checkbox" value="1" class="form-check-input"/>
                        <label class="form-check-label" for="test-1">1</label>
                    </div>
                </div>',
                '<x-bs::form.field name="test" type="checkbox" :options="\Portavice\Bladestrap\Support\Options::one(1)" value="1">Test</x-bs::form.field>',
                [],
            ],

            // type radio
            [
                ['test' => ['1', '3']], // old value: 1 and 3 selected
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <div class="form-check">
                        <input id="test-1" name="test[]" type="radio" value="1" class="form-check-input" checked/>
                        <label class="form-check-label" for="test-1">1</label>
                    </div>
                    <div class="form-check">
                        <input id="test-2" name="test[]" type="radio" value="2" class="form-check-input"/>
                        <label class="form-check-label" for="test-2">2</label>
                    </div>
                    <div class="form-check">
                        <input id="test-3" name="test[]" type="radio" value="3" class="form-check-input" checked/>
                        <label class="form-check-label" for="test-3">3</label>
                    </div>
                </div>',
                '<x-bs::form.field id="test" name="test[]" type="radio" :options="[1 => 1, 2 => 2, 3 => 3]" cast="int">Test</x-bs::form.field>',
                [],
            ],

            // type text
            [
                ['test' => ''], // old value (changed to empty value)
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <input id="test" name="test" type="text" value="" class="form-control"/>
                </div>',
                '<x-bs::form.field name="test" type="text" value="test-value">Test</x-bs::form.field>',
                [],
            ],
            [
                ['test' => 'another-value'], // old value (changed to another value)
                '<div class="mb-3">
                    <label for="test" class="form-label">Test</label>
                    <input id="test" name="test" type="text" value="another-value" class="form-control"/>
                </div>',
                '<x-bs::form.field name="test" type="text" value="test-value">Test</x-bs::form.field>',
                [],
            ],
            [
                ['test1' => 'another-value', 'test2' => ''], // old values
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
                ['value1' => 'test-value-1', 'value2' => 'test-value-2'],
            ],
        ];
    }

    private function mockOld(array $old): void
    {
        $mockRequest = $this->mock(Request::class);
        $mockRequest->allows('setUserResolver')->andReturns($mockRequest);

        /** Answer @see old() calls */
        $mockRequest->allows('old')
            ->andReturnUsing(static function ($key, $default = null) use ($old) {
                if ($key === null) {
                    return $old;
                }

                return $old[$key] ?? $default ?? null;
            });

        $this->app->instance('request', $mockRequest);
    }
}
