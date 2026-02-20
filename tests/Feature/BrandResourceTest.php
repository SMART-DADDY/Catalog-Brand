<?php

use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Support\Icons\Heroicon;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\BrandResource;
use SmartDaddy\CatalogBrand\Models\Brand;
use App\Models\Store;

uses(RefreshDatabase::class);

it('exposes expected global search metadata', function (): void {
    $record = new Brand([
        'name' => 'Frozen Foods',
        'description' => 'Frozen products',
    ]);

    expect(BrandResource::getGloballySearchableAttributes())->toBe(['name', 'description'])
        ->and((string) BrandResource::getGlobalSearchResultTitle($record))->toBe('Frozen Foods')
        ->and(BrandResource::getGlobalSearchResultDetails($record))->toBe([
            'Description' => 'Frozen products',
        ]);
});

it('defines expected resource pages and removes soft delete scope for binding query', function (): void {
    $pages = BrandResource::getPages();

    expect(array_keys($pages))->toBe(['index', 'create', 'view', 'edit']);

    $store = Store::create(['name' => 'Main Store']);

    $brand = Brand::create([
        'store_id' => $store->id,
        'name' => 'Soft Deleted',
        'description' => 'to test binding query',
    ]);

    $brand->delete();

    $query = BrandResource::getRecordRouteBindingEloquentQuery();

    expect($query->whereKey($brand->id)->first())->not->toBeNull();
});

it('exposes expected navigation metadata', function (): void {
    expect(BrandResource::getNavigationIcon())->toBe(Heroicon::OutlinedTag)
        ->and(BrandResource::getActiveNavigationIcon())->toBe(Heroicon::Tag)
        ->and(BrandResource::getNavigationGroup())->toBe('Inventory')
        ->and(BrandResource::getNavigationSort())->toBe(3);
});
