<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class ButtonTest extends ComponentTestCase
{
    use TestsVariants;

    #[DataProvider('variants')]
    public function testButtonsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<button class="btn ' . $buttonClass . '">Button title</button>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button %s>Button title</x-bs::button>',
                    self::makeVariantAttribute($variant)
                )
            )
        );
    }

    #[DataProvider('variants')]
    public function testDisabledButtonsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<button aria-disabled="true" tabindex="-1" class="btn ' . $buttonClass . ' disabled" disabled>Button title</button>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button %s :disabled="true">Button title</x-bs::button>',
                    self::makeVariantAttribute($variant)
                )
            )
        );
    }

    public static function variants(): array
    {
        return [
            ['btn-primary', null],
            ...self::makeDataProvider('btn-'),
            ['btn-link', 'link'],
        ];
    }
}
