<?php

namespace Portavice\Bladestrap\Tests\Feature\Form;

use Portavice\Bladestrap\Tests\Feature\ComponentTestCase;

class FormTest extends ComponentTestCase
{
    public function testFormRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtmlAfterInjectingCsrfToken(
            '<form method="POST" action="https://example.org/index.php" class="my-3">
                <input type="hidden" name="_token" value="CSRF_TOKEN_PLACEHOLDER" autocomplete="off"> ...
            </form>',
            $this->bladeView('<x-bs::form action="https://example.org/index.php" class="my-3">
                ...
            </x-bs::form>')
        );
    }

    public function testFormWithGetMethodRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtml(
            '<form method="GET" action="">
                ...
            </form>',
            '<x-bs::form method="GET" action="">...</x-bs::form>'
        );
    }

    public function testFormWithPutMethodRendersCorrectly(): void
    {
        $this->assertBladeRendersToHtmlAfterInjectingCsrfToken(
            <<<EOT
            <form method="POST" action="test.php">
                <input type="hidden" name="_token" value="CSRF_TOKEN_PLACEHOLDER" autocomplete="off"> <input type="hidden" name="_method" value="PUT"> ...
            </form>
EOT,
            $this->bladeView('<x-bs::form method="PUT" action="test.php">...</x-bs::form>')
        );
    }
}
