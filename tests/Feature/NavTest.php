<?php

namespace Portavice\Bladestrap\Tests\Feature;

class NavTest extends ComponentTestCase
{
    public function testNavRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<ul class="nav">
                ...
            </ul>',
            $this->bladeView('<x-bs::nav>...</x-bs::nav>')
        );
        $this->assertBladeRendersToHtml(
            '<nav class="nav">
                ...
            </nav>',
            $this->bladeView('<x-bs::nav container="nav">...</x-bs::nav>')
        );
        $this->assertBladeRendersToHtml(
            '<nav class="nav flex-column">
                ...
            </nav>',
            $this->bladeView('<x-bs::nav container="nav" :vertical="$vertical">...</x-bs::nav>', [
                'vertical' => true,
            ])
        );
    }

    public function testNavItemRendersCorrectlyForCurrentPage(): void
    {
        $this->mockRequest('http://localhost/current-page');
        $this->assertBladeRendersToHtml(
            '<li class="nav-item">
                <a aria-current="page"  class="nav-link active" href="http://localhost/current-page">Current Page</a>
            </li>',
            $this->bladeView('<x-bs::nav.item href="http://localhost/current-page">Current Page</x-bs::nav.item>')
        );
    }

    public function testNavDropdownRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" id="myDropdownLink">Dropdown title</a>
                <ul  aria-labelledby="myDropdownLink" class="dropdown-menu dropdown-menu-end"><li><a class="dropdown-item" href="#">Action</a></li></ul>
            </li>',
            $this->bladeView('<x-bs::nav.item id="myDropdownLink">
                Dropdown title
                <x-slot:dropdown class="dropdown-menu-end">
                      <li><a class="dropdown-item" href="#">Action</a></li>
                </x-slot:dropdown>
            </x-bs::nav.item>')
        );
    }
}
