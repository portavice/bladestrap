<?php

namespace Portavice\Bladestrap\Tests\Feature;

use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class ListTest extends ComponentTestCase
{
    use TestsVariants;

    /**
     * @dataProvider listVariables
     */
    public function testListGroupRendersCorrectly(string $additionalClasses, string $variables): void
    {
        $this->assertBladeRendersToHtml(
            '<ul class="list-group ' . $additionalClasses . '">
                ...
            </ul>',
            $this->bladeView(sprintf('<x-bs::list %s>...</x-bs::list>', $variables))
        );
    }

    public static function listVariables(): array
    {
        return [
            ['', ''],
            ['', ':flush="false"'],
            ['list-group-flush', ':flush="true"'],
            ['list-group-horizontal list-group-flush', ':flush="true" :horizontal="true"'],
            ['list-group-horizontal', ':horizontal="true"'],
        ];
    }

    public function testListGroupWithContainerRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="list-group">
                ...
            </div>',
            $this->bladeView('<x-bs::list container="div">...</x-bs::list>')
        );
    }

    /**
     * @dataProvider variants
     */
    public function testListItemsRenderCorrectly(string $additionalClasses, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<li class="list-group-item ' . $additionalClasses . '">...</li>',
            $this->bladeView(sprintf('<x-bs::list.item %s>...</x-bs::list.item>', self::makeVariantAttribute($variant)))
        );
    }

    public static function variants(): array
    {
        return [
            ['', null],
            ...self::makeDataProvider('list-group-item-'),
        ];
    }

    public function testActiveListItemRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li aria-current="true" class="list-group-item active">...</li>',
            $this->bladeView('<x-bs::list.item :active="true">...</x-bs::list.item>')
        );
    }

    public function testDisabledListItemWithContainerRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li aria-disabled="true" class="list-group-item disabled">Link title</li>',
            $this->bladeView('<x-bs::list.item :disabled="true">Link title</x-bs::list.item>')
        );
    }

    public function testListItemWithContainerRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<a class="list-group-item" href="http://localhost/my-target">Link title</a>',
            $this->bladeView('<x-bs::list.item container="a" href="http://localhost/my-target">Link title</x-bs::list.item>')
        );
    }

    public function testListItemWithEndRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li class="list-group-item d-flex justify-content-between align-items-center">Item <span class="badge bg-primary rounded-pill">14</span></li>',
            $this->bladeView('<x-bs::list.item>
                Item
                <x-slot:end><span class="badge bg-primary rounded-pill">14</span></x-slot:end>
            </x-bs::list.item>')
        );
    }
}
