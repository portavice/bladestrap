<?php

namespace Feature\Modal;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class ModalTest extends ComponentTestCase
{
    #[DataProvider('modalOptions')]
    public function testModalRendersOptionsCorrectly(string $blade, string $modalAttributes, string $modalDialogClasses): void
    {
        $this->assertBladeRendersToHtml(
            '<div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" ' . $modalAttributes . '>
                <div class="' . $modalDialogClasses . '">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">My modal</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>',
            $this->bladeView('<x-bs::modal id="exampleModal" ' . $blade .'>My modal</x-bs::modal>')
        );
    }

    public static function modalOptions(): array
    {
        return [
            ['', 'class="modal fade"', 'modal-dialog'],
            [':centered="true"', 'class="modal fade"', 'modal-dialog modal-dialog-centered'],
            [':centered="true" :scrollable="true"', 'class="modal fade"', 'modal-dialog modal-dialog-centered modal-dialog-scrollable'],
            [':fade="false"', 'class="modal"', 'modal-dialog'],
            [':scrollable="true"', 'class="modal fade"', 'modal-dialog modal-dialog-scrollable'],
            [':static-backdrop="true"', 'data-bs-backdrop="static" data-bs-keyboard="false" class="modal fade"', 'modal-dialog'],
        ];
    }

    #[DataProvider('fullScreenOptions')]
    public function testModalRendersFullScreenOptionsCorrectly(bool|string $fullScreen, string $modalDialogClass): void
    {
        $this->assertBladeRendersToHtml(
            '<div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
                <div class="modal-dialog ' . $modalDialogClass . '">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">My modal</div>
                        <div class="modal-footer">
                            <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>',
            $this->bladeView('<x-bs::modal id="exampleModal" ' . $fullScreen .'>My modal</x-bs::modal>')
        );
    }

    public static function fullScreenOptions(): array
    {
        return [
            ['', ''],
            [':full-screen="false"', ''],
            [':full-screen="true"', 'modal-fullscreen'],
            ['full-screen="sm"', 'modal-fullscreen-sm-down'],
            ['full-screen="md"', 'modal-fullscreen-md-down'],
            ['full-screen="lg"', 'modal-fullscreen-lg-down'],
            ['full-screen="xl"', 'modal-fullscreen-xl-down'],
            ['full-screen="xxl"', 'modal-fullscreen-xxl-down'],
        ];
    }

    #[DataProvider('closeButtonOptions')]
    public function testModalRendersCloseButtonCorrectly(string $closeButtonAttributes, string $footer): void
    {
        $this->assertBladeRendersToHtml(
            '<div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">My modal</div>'
                            . ($footer ? "\n" . $footer : '') . '
                        </div>
                </div>
            </div>',
            $this->bladeView('<x-bs::modal id="exampleModal" ' . $closeButtonAttributes . '>My modal</x-bs::modal>')
        );
    }

    public static function closeButtonOptions(): array
    {
        return [
            ['', self::makeFooter('btn-secondary')],
            [':close-button="true"', self::makeFooter('btn-secondary')],
            [':close-button="false"', ''],
            ['close-button="primary"', self::makeFooter('btn-primary')],
        ];
    }

    #[DataProvider('slots')]
    public function testModalRendersSlotsCorrectly(string $slots, string $header, string $footer): void
    {
        $this->assertBladeRendersToHtml(
            '<div id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true" class="modal fade">
                <div class="modal-dialog">
                    <div class="modal-content">
                        ' . $header . '
                        <div class="modal-body">My modal</div>
                        ' . $footer . '
                    </div>
                </div>
            </div>',
            $this->bladeView('<x-bs::modal id="exampleModal">My modal' . $slots . '</x-bs::modal>')
        );
    }

    public static function slots(): array
    {
        return [
            [
                '<x-slot:title class="fs-5">Test title</x-slot>',
                self::makeHeader('<h1 id="exampleModalLabel" class="modal-title fs-5">Test title</h1>'),
                self::makeFooter('btn-secondary'),
            ],
            [
                '<x-slot:title container="h2" class="text-primary">Test title</x-slot>',
                self::makeHeader('<h2 id="exampleModalLabel" class="modal-title text-primary">Test title</h2>'),
                self::makeFooter('btn-secondary'),
            ],
            [
                '<x-slot:footer>
                    <x-bs::button variant="primary">Submit</x-bs:button>
                </x-slot>',
                self::makeHeader(null),
                '<div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-primary">Submit</button></div>',
            ],
        ];
    }

    private static function makeHeader(?string $title): string
    {
        return '<div class="modal-header">'
            . ($title ? "\n" . $title : '') . '
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>';
    }

    private static function makeFooter(?string $buttonClass): string
    {
        if ($buttonClass === null) {
            return '';
        }

        return '<div class="modal-footer">
            <button class="btn ' . $buttonClass .'" type="button" data-bs-dismiss="modal">Close</button>
        </div>';
    }
}
