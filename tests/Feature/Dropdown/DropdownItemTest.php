<?php

namespace Portavice\Bladestrap\Tests\Feature\Dropdown;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class DropdownItemTest extends ComponentTestCase
{
    public function testDropdownItemRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li><a class="dropdown-item" href="http://localhost/my-account">My account</a></li>',
            $this->bladeView('<x-bs::dropdown.item href="http://localhost/my-account">My account</x-bs::dropdown.item>')
        );
    }

    public function testActiveDropdownItemRendersCorrectly(): void
    {
        $this->mockRequest('http://localhost/my-account');
        $this->assertBladeRendersToHtml(
            '<li><a aria-current="true" class="dropdown-item active" href="http://localhost/my-account">My account</a></li>',
            $this->bladeView('<x-bs::dropdown.item href="http://localhost/my-account">My account</x-bs::dropdown.item>')
        );
    }

    public function testDropdownItemWithSubItemsRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li><a class="dropdown-item" href="http://localhost/my-account">My account</a> ' .
            '<ul class="list-unstyled small">
                    <li><a class="dropdown-item" href="http://localhost/my-account/orders"><span class="ms-4">My orders</span></a></li>
                    <li><a class="dropdown-item" href="http://localhost/my-account/invoices"><span class="ms-4">My invoices</span></a></li>
                </ul>
            </li>',
            $this->bladeView(
                '<x-bs::dropdown.item href="http://localhost/my-account">
                    My account
                    <x-slot name="subItems">
                        <li><a class="dropdown-item" href="http://localhost/my-account/orders"><span class="ms-4">My orders</span></a></li>
                        <li><a class="dropdown-item" href="http://localhost/my-account/invoices"><span class="ms-4">My invoices</span></a></li>
                    </x-slot>
                </x-bs::dropdown.item>'
            )
        );
    }

    public function testDropdownSubItemRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li><a class="dropdown-item" href="http://localhost/my-account/orders"><span class="ms-4">My orders</span></a></li>',
            $this->bladeView('<x-bs::dropdown.item href="http://localhost/my-account/orders" :isSubItem="true">My orders</x-bs::dropdown.item>')
        );
    }
}
