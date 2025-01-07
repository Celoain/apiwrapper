<?php

namespace Celoain\ApiWrapper;

use Illuminate\Support\ServiceProvider;

class ApiWrapperServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-wrapper.php', 'api-wrapper');

        $this->app->singleton('api-wrapper', function () {
            return new ApiWrapper;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array<string>
     */
    public function provides()
    {
        return ['api-wrapper'];
    }

    /**
     * Console-specific booting.
     */
    protected function bootForConsole(): void
    {
        $this->publishes([
            __DIR__.'/../config/api-wrapper.php' => config_path('api-wrapper.php'),
        ], 'api-wrapper.config');
    }
}
