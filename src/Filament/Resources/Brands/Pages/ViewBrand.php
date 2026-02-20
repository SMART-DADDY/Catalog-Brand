<?php

namespace SmartDaddy\CatalogBrand\Filament\Resources\Brands\Pages;

use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Database\Eloquent\Model;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\BrandResource;

class ViewBrand extends ViewRecord
{
    protected static string $resource = BrandResource::class;

    protected function resolveRecord(int|string $key): Model
    {
        $record = parent::resolveRecord($key);

        if (method_exists($record, 'activity')) {
            return $record->load(['activity.creator', 'activity.updater']);
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make()
                ->label('Edit')
                ->color('warning'),
            DeleteAction::make()
                ->label('Delete')
                ->color('danger'),
            RestoreAction::make()
                ->label('Restore')
                ->color('success'),
            ForceDeleteAction::make()
                ->label('Force delete')
                ->color('warning'),
        ];
    }
}
