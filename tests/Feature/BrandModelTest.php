<?php

use App\Models\Store;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use SmartDaddy\CatalogBrand\Enums\BrandStatus;
use SmartDaddy\CatalogBrand\Models\Brand;

uses(RefreshDatabase::class);

it('creates expected schema and applies default status', function (): void {
    expect(Schema::hasTable('brands'))->toBeTrue()
        ->and(Schema::hasColumns('brands', [
            'id',
            'store_id',
            'name',
            'description',
            'status',
            'deleted_at',
            'created_at',
            'updated_at',
        ]))->toBeTrue();

    $store = Store::create(['name' => 'Main Store']);

    $brand = Brand::create([
        'store_id' => $store->id,
        'name' => 'Beverages',
        'description' => 'Cold and hot drinks',
    ]);
    $brand->refresh();

    expect($brand->status)->toBe(BrandStatus::Active)
        ->and($brand->getRawOriginal('status'))->toBe(BrandStatus::Active->value);
});

it('defines fillable fields, casts and store relation', function (): void {
    $store = Store::create(['name' => 'Main Store']);

    $brand = Brand::create([
        'store_id' => $store->id,
        'name' => 'Bakery',
        'description' => 'Fresh breads',
        'status' => BrandStatus::Draft,
    ]);

    expect($brand->getFillable())->toBe(['store_id', 'name', 'description', 'status'])
        ->and($brand->status)->toBe(BrandStatus::Draft)
        ->and($brand->store())->toBeInstanceOf(BelongsTo::class)
        ->and($brand->store()->getRelated()::class)->toBe(Store::class)
        ->and(class_uses_recursive(Brand::class))->toContain(SoftDeletes::class);
});

it('cascades brand deletion when store is removed', function (): void {
    $store = Store::create(['name' => 'Main Store']);

    $brand = Brand::create([
        'store_id' => $store->id,
        'name' => 'Dairy',
        'description' => 'Milk and cheese',
        'status' => BrandStatus::Inactive,
    ]);

    $store->delete();

    expect(Brand::query()->whereKey($brand->id)->exists())->toBeFalse();
});
