<?php

namespace Portavice\Bladestrap\Tests\Feature;

class BreadcrumbTest extends ComponentTestCase
{
    public function testBreadcrumbRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<nav class="mt-3" aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2">
                    <li class="breadcrumb-item">
                        <a href="/">Dashboard</a>
                    </li>
                </ol>
            </nav>',
            $this->bladeView(
                '<x-bs::breadcrumb container-class="mt-3" class="bg-light p-2">
                    <x-bs::breadcrumb.item href="/">Dashboard</x-bs::breadcrumb.item>
                </x-bs::breadcrumb>'
            )
        );
    }

    public function testBreadcrumbItemRendersCorrectlyForCurrentPage(): void
    {
        $this->mockRequest('http://localhost/current-page');
        $this->assertBladeRendersToHtml(
            '<li aria-current="page" class="breadcrumb-item active">
                <a href="http://localhost/current-page">Current Page</a>
            </li>',
            $this->bladeView('<x-bs::breadcrumb.item href="http://localhost/current-page">Current Page</x-bs::breadcrumb.item>')
        );
    }

    public function testBreadcrumbItemRendersCorrectlyForPageWithoutHref(): void
    {
        $this->mockRequest('http://localhost/current-page');
        $this->assertBladeRendersToHtml(
            '<li aria-current="page" class="breadcrumb-item active">
                Current Page
            </li>',
            $this->bladeView('<x-bs::breadcrumb.item>Current Page</x-bs::breadcrumb.item>')
        );
    }
}
