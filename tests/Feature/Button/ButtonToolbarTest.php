<?php

namespace Portavice\Bladestrap\Tests\Feature\Button;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class ButtonToolbarTest extends ComponentTestCase
{
    public function testButtonToolbarRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<div role="toolbar" class="btn-toolbar">...</div>',
            $this->bladeView('<x-bs::button.toolbar>...</x-bs::button.group>')
        );
    }
}
