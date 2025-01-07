<?php

namespace Celoain\ApiWrapper;

use Illuminate\Support\ServiceProvider;

class ApiWrapperServiceProvider extends ServiceProvider
{
    /**
     * Register any package services.
     */
    public function register(): void
    {
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
}
