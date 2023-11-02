<?php

namespace Portavice\Bladestrap\Tests\Feature;

use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class AlertTest extends ComponentTestCase
{
    use TestsVariants;

    /**
     * @dataProvider variants
     */
    public function testAlertsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="alert" class="alert ' . $buttonClass . '">Alert</div>',
            $this->bladeView(sprintf('<x-bs::alert %s>Alert</x-bs::button>', self::makeVariantAttribute($variant)))
        );
    }

    /**
     * @dataProvider variants
     */
    public function testDismissibleAlertsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="alert" class="alert ' . $buttonClass . ' alert-dismissible fade show">Alert '
                . '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"/></div>',
            $this->bladeView(sprintf('<x-bs::alert %s dismissible="true">Alert</x-bs::button>', self::makeVariantAttribute($variant)))
        );
    }

    public static function variants(): array
    {
        return [
            ...self::makeDataProvider('alert-'),
            ['alert-info', null],
        ];
    }
}
