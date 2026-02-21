<?php

namespace SmartDaddy\CatalogBrand\Filament\Resources\Brands\Schemas;

use Filament\Facades\Filament;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class BrandInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Grid::make(3)
                    ->schema([
                        Grid::make()
                            ->schema([
                                Section::make('Brand Information')
                                    ->schema([
                                        TextEntry::make('name')
                                            ->label('Brand Name')
                                            ->placeholder('No name provided')
                                            ->weight('semibold')
                                            ->size('lg'),
                                        TextEntry::make('description')
                                            ->label('Description')
                                            ->placeholder('No description provided')
                                            ->columnSpanFull(),
                                    ])
                                    ->columns(2)
                                    ->columnSpanFull(),
                            ])
                            ->columnSpan(2),
                        Grid::make()
                            ->schema([
                                Section::make('Brand Status')
                                    ->schema([
                                        TextEntry::make('status')
                                            ->label('Current Status')
                                            ->badge()
                                            ->hintIcon(fn ($r) => $r?->status?->getIcon())
                                            ->helperText(fn ($r) => $r?->status?->getDescription())
                                            ->size('lg'),
                                    ])
                                    ->columnSpanFull(),
                                Section::make('Store Information')
                                    ->schema([
                                        TextEntry::make('store.name')
                                            ->label('Store')
                                            ->placeholder('No store assigned')
                                            ->weight('medium'),
                                    ])
                                    ->columnSpanFull(),
                                Section::make('Record Information')
                                    ->schema([
                                        TextEntry::make('created_at')
                                            ->label('Created')
                                            ->since()
                                            ->timezone(fn () => Filament::getTenant()?->timezone?->name ?? 'UTC')
                                            ->placeholder('—'),
                                        TextEntry::make('updated_at')
                                            ->label('Last Updated')
                                            ->since()
                                            ->timezone(fn () => Filament::getTenant()?->timezone?->name ?? 'UTC')
                                            ->placeholder('—'),
                                        TextEntry::make('deleted_at')
                                            ->label('Deleted')
                                            ->dateTime()
                                            ->timezone(fn () => Filament::getTenant()?->timezone?->name ?? 'UTC')
                                            ->hidden(fn ($r) => ! $r?->deleted_at)
                                            ->placeholder('—'),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull(),
                                Section::make('User Activity')
                                    ->schema([
                                        TextEntry::make('activity.created_by')
                                            ->label('Created By')
                                            ->getStateUsing(fn ($record) => method_exists($record, 'activity') ? ($record->activity?->creator?->name ?? 'Unknown') : null)
                                            ->placeholder('Unknown')
                                            ->icon(Heroicon::OutlinedUserPlus)
                                            ->visible(fn ($record) => method_exists($record, 'activity') && (bool) $record->activity?->creator),
                                        TextEntry::make('activity.updated_by')
                                            ->label('Last Updated By')
                                            ->getStateUsing(fn ($record) => method_exists($record, 'activity') ? ($record->activity?->updater?->name ?? 'Not updated yet') : null)
                                            ->placeholder('Not updated yet')
                                            ->icon(Heroicon::OutlinedPencilSquare)
                                            ->visible(fn ($record) => method_exists($record, 'activity') && (bool) $record->activity?->updater),
                                    ])
                                    ->columns(1)
                                    ->columnSpanFull()
                                    ->visible(fn ($record) => method_exists($record, 'activity')),
                            ])
                            ->columnSpan(1),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
