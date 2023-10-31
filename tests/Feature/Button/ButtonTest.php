<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class ButtonTest extends ComponentTestCase
{
    /**
     * @dataProvider dataProviderForVariants
     */
    public function testButtonsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<button class="btn ' . $buttonClass . '">Button title</button>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button %s>Button title</x-bs::button>',
                    isset($variant) ? sprintf('variant="%s"', $variant) : ''
                )
            )
        );
    }

    /**
     * @dataProvider dataProviderForVariants
     */
    public function testDisabledButtonsRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<button aria-disabled="true" tabindex="-1" class="btn ' . $buttonClass . ' disabled" disabled>Button title</button>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button %s disabled="true">Button title</x-bs::button>',
                    isset($variant) ? sprintf('variant="%s"', $variant) : ''
                )
            )
        );
    }

    public static function dataProviderForVariants(): array
    {
        return [
            ['btn-primary', null],
            ['btn-primary', 'primary'],
            ['btn-secondary', 'secondary'],
            ['btn-danger', 'danger'],
            ['btn-link', 'link'],
        ];
    }
}
