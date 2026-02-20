<?php

namespace SmartDaddy\CatalogBrand\Filament\Resources\Brands;

use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\CreateBrand;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\EditBrand;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\ListBrands;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages\ViewBrand;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Schemas\BrandForm;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Schemas\BrandInfolist;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Tables\BrandsTable;
use SmartDaddy\CatalogBrand\Models\Brand;
use UnitEnum;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTag;

    protected static string|UnitEnum|null $navigationGroup = 'Inventory';

    protected static ?int $navigationSort = 3;

    public static function getActiveNavigationIcon(): BackedEnum|Htmlable|null|string
    {
        return Heroicon::Tag;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'description'];
    }

    public static function getGlobalSearchResultTitle(Model $record): string|Htmlable
    {
        return $record->name;
    }

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Description' => $record->description,
        ];
    }

    public static function form(Schema $schema): Schema
    {
        return BrandForm::configure($schema);
    }

    public static function infolist(Schema $schema): Schema
    {
        return BrandInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBrands::route('/'),
            'create' => CreateBrand::route('/create'),
            'view' => ViewBrand::route('/{record}'),
            'edit' => EditBrand::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
