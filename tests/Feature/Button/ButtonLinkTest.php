<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class ButtonLinkTest extends ComponentTestCase
{
    use TestsVariants;

    #[DataProvider('variants')]
    public function testLinksRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<a class="btn ' . $buttonClass . '" href="http://localhost/my-target">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button.link %s href="http://localhost/my-target">Link title</x-bs::button.link>',
                    self::makeVariantAttribute($variant)
                )
            )
        );
    }

    #[DataProvider('variants')]
    public function testDisabledLinksRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        /*
         * No href as recommended in https://getbootstrap.com/docs/5.3/components/buttons/#disabled-state
         */
        $this->assertBladeRendersToHtml(
            '<a aria-disabled="true" class="btn ' . $buttonClass . ' disabled">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button.link %s :disabled="true">Link title</x-bs::button>',
                    self::makeVariantAttribute($variant)
                )
            )
        );

        /**
         * Additional tabindex="-1" for links with href recommended in
         * https://getbootstrap.com/docs/5.3/components/buttons/#link-functionality-caveat
         */
        $this->assertBladeRendersToHtml(
            '<a aria-disabled="true" role="button" tabindex="-1" class="btn ' . $buttonClass . ' disabled" href="#">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button.link %s disabled="true" href="#">Link title</x-bs::button>',
                    isset($variant) ? sprintf('variant="%s"', $variant) : ''
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
