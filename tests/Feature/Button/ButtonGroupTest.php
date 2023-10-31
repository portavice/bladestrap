<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class ButtonGroupTest extends ComponentTestCase
{
    public function testButtonGroupRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="group" class="btn-group"><button class="btn btn-primary">Button 1</button>
                <button class="btn btn-primary">Button 2</button></div>',
            $this->bladeView(
                '<x-bs::button.group>
                    <x-bs::button>Button 1</x-bs::button>
                    <x-bs::button>Button 2</x-bs::button>
                </x-bs::button.group>'
            )
        );
    }

    public function testVerticalButtonGroupRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="group" class="btn-group-vertical"><button class="btn btn-primary">Button 1</button>
                <button class="btn btn-primary">Button 2</button></div>',
            $this->bladeView(
                '<x-bs::button.group :vertical="true">
                    <x-bs::button>Button 1</x-bs::button>
                    <x-bs::button>Button 2</x-bs::button>
                </x-bs::button.group>'
            )
        );
    }
}
