# smart-daddy/catalog-brand

Brand module package for Laravel + Filament applications.

This package provides:
- `Brand` model
- `BrandStatus` enum
- brand migration
- Filament Brand resource (pages, table, form, infolist)
- brand importer and exporter

## Requirements

- PHP 8.4+
- Laravel 12+
- Filament 5+

## Installation

```bash
composer require smart-daddy/catalog-brand
```

## Migration

```bash
php artisan migrate
```

The package registers its migration through `CatalogBrandServiceProvider`.

## Filament integration

Register package resources in your panel provider:

```php
->discoverResources(
    in: base_path('vendor/smart-daddy/catalog-brand/src/Filament/Resources'),
    for: 'SmartDaddy\\CatalogBrand\\Filament\\Resources'
)
```

If your project uses an in-repo path package, use your local package path instead of `vendor/...`.

## Optional User Activity integration

If `smart-daddy/user-activity` is installed, `Brand` will automatically use `TracksUserActivity`.
If it is not installed, `Brand` still works normally without activity tracking.

## Package identity

- Composer name: `smart-daddy/catalog-brand`
- Root namespace: `SmartDaddy\\CatalogBrand\\`
- Service provider: `SmartDaddy\\CatalogBrand\\CatalogBrandServiceProvider`

## License

MIT
