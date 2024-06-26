<?php

namespace Portavice\Bladestrap\Tests\Feature;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class BadgeTest extends ComponentTestCase
{
    use TestsVariants;

    #[DataProvider('variants')]
    public function testBadgesRenderCorrectly(string $buttonClass, string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<span class="badge ' . $buttonClass . '">' . $variant . '</span>',
            $this->bladeView(
                sprintf(
                    '<x-bs::badge %s>%s</x-bs::button>',
                    self::makeVariantAttribute($variant),
                    $variant
                )
            )
        );
    }

    public static function variants(): array
    {
        return self::makeDataProvider('text-bg-');
    }
}
