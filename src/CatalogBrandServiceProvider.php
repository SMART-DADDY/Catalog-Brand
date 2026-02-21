<?php

namespace SmartDaddy\CatalogBrand;

use Illuminate\Support\ServiceProvider;

class CatalogBrandServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/catalog-brand.php', 'catalog-brand');
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->publishes([
            __DIR__.'/../config/catalog-brand.php' => config_path('catalog-brand.php'),
        ], 'catalog-brand-config');
    }
}
