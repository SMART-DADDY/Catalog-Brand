<?php

use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\BrandResource;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\CreateBrand;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\EditBrand;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\ListBrands;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\ViewBrand;

class FakeBrandResource
{
    public static function getUrl(string $name): string
    {
        return $name === 'index' ? '/brands' : '/';
    }
}

class TestListBrandsPage extends ListBrands
{
    public function exposedHeaderActions(): array
    {
        return $this->getHeaderActions();
    }
}

class TestEditBrandPage extends EditBrand
{
    protected static string $resource = FakeBrandResource::class;

    public function exposedHeaderActions(): array
    {
        return $this->getHeaderActions();
    }

    public function exposedRedirectUrl(): string
    {
        return $this->getRedirectUrl();
    }
}

class TestCreateBrandPage extends CreateBrand
{
    protected static string $resource = FakeBrandResource::class;

    public function exposedRedirectUrl(): string
    {
        return $this->getRedirectUrl();
    }
}

class TestViewBrandPage extends ViewBrand
{
    public function exposedHeaderActions(): array
    {
        return $this->getHeaderActions();
    }
}

it('binds each page to the brand resource', function (): void {
    expect((new ReflectionClass(ListBrands::class))->getStaticPropertyValue('resource'))
        ->toBe(BrandResource::class)
        ->and((new ReflectionClass(CreateBrand::class))->getStaticPropertyValue('resource'))
        ->toBe(BrandResource::class)
        ->and((new ReflectionClass(EditBrand::class))->getStaticPropertyValue('resource'))
        ->toBe(BrandResource::class)
        ->and((new ReflectionClass(ViewBrand::class))->getStaticPropertyValue('resource'))
        ->toBe(BrandResource::class);
});

it('configures list page header actions', function (): void {
    $actions = (new TestListBrandsPage())->exposedHeaderActions();

    expect($actions)->toHaveCount(1)
        ->and($actions[0])->toBeInstanceOf(CreateAction::class);
});

it('configures create page redirect url', function (): void {
    expect((new TestCreateBrandPage())->exposedRedirectUrl())->toBe('/brands');
});

it('configures edit page header actions and redirect', function (): void {
    $page = new TestEditBrandPage();
    $actions = $page->exposedHeaderActions();

    expect($actions)->toHaveCount(3)
        ->and($actions[0])->toBeInstanceOf(DeleteAction::class)
        ->and($actions[1])->toBeInstanceOf(ForceDeleteAction::class)
        ->and($actions[2])->toBeInstanceOf(RestoreAction::class)
        ->and($page->exposedRedirectUrl())->toBe('/brands');
});

it('configures view page header actions', function (): void {
    $actions = (new TestViewBrandPage())->exposedHeaderActions();

    expect($actions)->toHaveCount(4)
        ->and($actions[0])->toBeInstanceOf(EditAction::class)
        ->and($actions[0]->getColor())->toBe('warning')
        ->and($actions[1])->toBeInstanceOf(DeleteAction::class)
        ->and($actions[1]->getColor())->toBe('danger')
        ->and($actions[2])->toBeInstanceOf(RestoreAction::class)
        ->and($actions[2]->getColor())->toBe('success')
        ->and($actions[3])->toBeInstanceOf(ForceDeleteAction::class)
        ->and($actions[3]->getColor())->toBe('warning');
});
