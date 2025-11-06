<?php

namespace Portavice\Bladestrap\Tests\Feature;

use Illuminate\Foundation\Testing\Concerns\InteractsWithSession;
use Illuminate\Foundation\Testing\Concerns\InteractsWithViews;
use Illuminate\Http\Request;
use Illuminate\Testing\TestView;
use Orchestra\Testbench\TestCase;
use Override;
use Portavice\Bladestrap\BladestrapServiceProvider;

abstract class ComponentTestCase extends TestCase
{
    use InteractsWithSession;
    use InteractsWithViews;

    #[Override]
    protected function getPackageProviders($app): array
    {
        return [
            BladestrapServiceProvider::class,
        ];
    }

    protected function assertBladeRendersToHtml(string $expectedHtml, string|TestView $blade): void
    {
        if (is_string($blade)) {
            $blade = $this->bladeView($blade);
        }

        $this->assertStringEqualsIgnoringWhitespace(
            $expectedHtml,
            $this->bladeView($blade)
        );
    }

    public function assertBladeRendersToHtmlAfterInjectingCsrfToken(string $expectedHtml, TestView $bladeView): void
    {
        preg_match('/name="_token" value="([^"]+)"/', $bladeView, $matches);
        $csrfToken = $matches[1] ?? '';
        $expectedHtml = str_replace('CSRF_TOKEN_PLACEHOLDER', $csrfToken, $expectedHtml);

        $this->assertBladeRendersToHtml($expectedHtml, $bladeView);
    }

    protected function bladeView(string $blade, array $data = [], array $errors = []): TestView
    {
        return $this->withViewErrors($errors)
            ->blade($blade, $data);
    }

    private function assertStringEqualsIgnoringWhitespace(string $expected, string $actual): void
    {
        $this->assertEquals(
            $this->removeSpaces($expected),
            $this->removeSpaces($actual)
        );
    }

    private function removeSpaces(string $string): string
    {
        // Reduce consecutive spaces to a single space.
        $string = preg_replace('/ +/', ' ', $string);

        // Remove whitespace at the beginning of a new line.
        $string = preg_replace('/\n +/', "\n", $string);

        // Replace spaces at the end of an HTML tag.
        $string = str_replace([' />', ' >', ' "'], ['/>', '>', '"'], $string);

        return trim($string);
    }

    protected function mockRequest(string $uri): void
    {
        $mockRequest = Request::create($uri);
        $this->app->instance('request', $mockRequest);
    }
}
