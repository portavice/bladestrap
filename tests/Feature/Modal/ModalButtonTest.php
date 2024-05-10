<?php

namespace Feature\Modal;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class ModalButtonTest extends ComponentTestCase
{
    use TestsVariants;

    #[DataProvider('variants')]
    public function testModalButtonRendersCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<button class="btn ' . $buttonClass . '" type="button" data-bs-toggle="modal" data-bs-target="#my-modal">Open modal</button>',
            sprintf(
                '<x-bs::modal.button %s modal="my-modal">Open modal</x-bs::modal.button>',
                self::makeVariantAttribute($variant)
            )
        );
    }

    public static function variants(): array
    {
        return [
            ['btn-primary', null],
            ...self::makeDataProvider('btn-'),
        ];
    }
}
