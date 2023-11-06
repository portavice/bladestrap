<?php

namespace Portavice\Bladestrap\Tests\Feature\Dropdown;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class DropdownHeaderTest extends ComponentTestCase
{
    public function testDropdownHeaderRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li class="dropdown-header">My header</li>',
            $this->bladeView('<x-bs::dropdown.header>My header</x-bs::dropdown.header>')
        );
    }
}
