<?php

namespace Portavice\Bladestrap;

use Illuminate\Support\ServiceProvider;
use Override;
use Portavice\Bladestrap\Macros\ComponentAttributeBagExtension;

class BladestrapServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        // Load view components.
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'bs');

        // Register macros.
        ComponentAttributeBagExtension::registerMacros();

        if ($this->app->runningInConsole()) {
            // Publish configuration and view file.
            $this->publishes([
                __DIR__ . '/../config/bladestrap.php' => config_path('bladestrap.php'),
            ], 'bladestrap-config');
            $this->publishes([
                __DIR__ . '/../resources/views' => resource_path('views/vendor/bladestrap'),
            ], 'bladestrap-views');
        }
    }

    #[Override]
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/bladestrap.php', 'bladestrap');
    }
}
