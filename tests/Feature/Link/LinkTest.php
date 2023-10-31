<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class LinkTest extends ComponentTestCase
{
    /**
     * @dataProvider dataProviderForVariants
     */
    public function testLinksRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<a class="btn ' . $buttonClass . '" href="http://localhost/my-target">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::link %s href="http://localhost/my-target">Link title</x-bs::link>',
                    isset($variant) ? sprintf('variant="%s"', $variant) : ''
                )
            )
        );
    }

    /**
     * @dataProvider dataProviderForVariants
     */
    public function testDisabledLinksRenderCorrectly(string $buttonClass, ?string $variant): void
    {
        /*
         * No href as recommended in https://getbootstrap.com/docs/5.3/components/buttons/#disabled-state
         */
        $this->assertBladeRendersToHtml(
            '<a aria-disabled="true" class="btn ' . $buttonClass . ' disabled">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::link %s disabled="true">Link title</x-bs::button>',
                    isset($variant) ? sprintf('variant="%s"', $variant) : ''
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
                    '<x-bs::link %s disabled="true" href="#">Link title</x-bs::button>',
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
