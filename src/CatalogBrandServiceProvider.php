<?php

namespace SmartDaddy\CatalogBrand;

use Illuminate\Support\ServiceProvider;

class CatalogBrandServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }
}
