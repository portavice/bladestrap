<?php

namespace Portavice\Bladestrap\Tests\Feature\Dropdown;

use PHPUnit\Framework\Attributes\DataProvider;
use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;
use Portavice\Bladestrap\Tests\Traits\TestsVariants;

class DropdownButtonTest extends ComponentTestCase
{
    use TestsVariants;

    #[DataProvider('variants')]
    public function testDropdownButtonRendersCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="dropdown">
                <button class="btn ' . $buttonClass . ' dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">My button</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="http://localhost/test1">Test1</a></li>
                    <li><a class="dropdown-item" href="http://localhost/test2">Test2</a></li>
                </ul>
            </div>',
            $this->bladeView(
                sprintf(
                    '<x-bs::dropdown.button %s>
                        My button
                        <x-slot:dropdown>
                            <x-bs::dropdown.item href="http://localhost/test1">Test1</x-bs::dropdown.item>
                            <x-bs::dropdown.item href="http://localhost/test2">Test2</x-bs::dropdown.item>
                        </x-slot:dropdown>
                    </x-bs::dropdown.button>',
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
        ];
    }

    public function testDropdownButtonReferencesDropdownMenu(): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="dropdown">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false" id="dropdown-button-link">My button</button>
                <ul aria-labelledby="dropdown-button-link" class="dropdown-menu">
                    ...
                </ul>
            </div>',
            $this->bladeView('<x-bs::dropdown.button id="dropdown-button-link">
                    My button
                    <x-slot:dropdown>
                        ...
                    </x-slot:dropdown>
                </x-bs::dropdown.button>')
        );
    }

    #[DataProvider('directions')]
    public function testDropdownButtonWithDirectionRendersCorrectly(string $containerClass, ?string $direction): void
    {
        $this->assertBladeRendersToHtml(
            '<div class="' . $containerClass . '">
                <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">My button</button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="http://localhost/test1">Test1</a></li>
                    <li><a class="dropdown-item" href="http://localhost/test2">Test2</a></li>
                </ul>
            </div>',
            $this->bladeView(
                sprintf(
                    '<x-bs::dropdown.button %s>
                        My button
                        <x-slot:dropdown>
                            <x-bs::dropdown.item href="http://localhost/test1">Test1</x-bs::dropdown.item>
                            <x-bs::dropdown.item href="http://localhost/test2">Test2</x-bs::dropdown.item>
                        </x-slot:dropdown>
                    </x-bs::dropdown.button>',
                    isset($direction)
                        ? sprintf('direction="%s"', $direction)
                        : ''
                )
            )
        );
    }

    public static function directions(): array
    {
        return [
            ['dropdown', null],
            ['dropdown', 'down'],
            ['dropdown-center', 'down-center'],
            ['dropup', 'up'],
            ['dropup-center', 'up-center'],
            ['dropstart', 'start'],
            ['dropend', 'end'],
        ];
    }

    #[DataProvider('variants')]
    public function testDropdownButtonInButtonGroupRendersCorrectly(string $buttonClass, ?string $variant): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="group" class="btn-group">' .
                '<div role="group" class="btn-group">' .
                    '<button class="btn ' . $buttonClass . ' dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">Dropdown in a group</button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="http://localhost/test1">Test1</a></li>
                        <li><a class="dropdown-item" href="http://localhost/test2">Test2</a></li>
                    </ul>' .
                '</div>' .
            '</div>',
            $this->bladeView(
                sprintf(
                    '<x-bs::button.group>
                        <x-bs::button.group>
                            <x-bs::dropdown.button %s :nested-in-group="true">
                                Dropdown in a group
                                <x-slot:dropdown>
                                    <x-bs::dropdown.item href="http://localhost/test1">Test1</x-bs::dropdown.item>
                                    <x-bs::dropdown.item href="http://localhost/test2">Test2</x-bs::dropdown.item>
                                </x-slot:dropdown>
                            </x-bs::dropdown.button>
                        </x-bs::button.group>
                    </x-bs::button.group>',
                    self::makeVariantAttribute($variant)
                )
            )
        );
    }
}
