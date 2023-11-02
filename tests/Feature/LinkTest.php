<?php

namespace Portavice\Bladestrap\Tests\Feature;

use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class LinkTest extends ComponentTestCase
{
    use TestsVariants;

    /**
     * @dataProvider variants
     */
    public function testLinksRenderCorrectly(string $linkClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<a class="' . $linkClass . '" href="http://localhost/my-target">Link title</a>',
            $this->bladeView(
                sprintf(
                    '<x-bs::link %s href="http://localhost/my-target">Link title</x-bs::button.link>',
                    self::makeVariantAttribute($variant)
                )
            )
        );
    }

    public static function variants(): array
    {
        return [
            ...self::makeDataProvider('link-'),
            ['link-body-emphasis', 'body-emphasis'],
        ];
    }

    public function testLinksWithOpacityRenderCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<a class="link-primary link-opacity-75 link-opacity-25-hover" href="http://localhost/my-target">Link title</a>',
            $this->bladeView('<x-bs::link href="http://localhost/my-target" opacity="75" opacityHover="25">Link title</x-bs::button.link>')
        );
    }
}
