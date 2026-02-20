<?php

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use SmartDaddy\CatalogBrand\Enums\BrandStatus;
use SmartDaddy\CatalogBrand\Filament\Resources\Brands\Schemas\BrandForm;
use Tests\Fixtures\Support\FakeSchemasLivewire;

it('configures brand form fields and defaults', function (): void {
    $schema = BrandForm::configure(Schema::make(new FakeSchemasLivewire()));

    $name = $schema->getComponentByStatePath('name');
    $description = $schema->getComponentByStatePath('description');
    $status = $schema->getComponentByStatePath('status');

    expect($name)->toBeInstanceOf(TextInput::class)
        ->and($name->isRequired())->toBeTrue()
        ->and($description)->toBeInstanceOf(Textarea::class)
        ->and($description->getColumnSpan('default'))->toBe('full')
        ->and($status)->toBeInstanceOf(Select::class)
        ->and($status->isRequired())->toBeTrue()
        ->and($status->isSearchable())->toBeTrue()
        ->and($status->isPreloaded())->toBeTrue()
        ->and($status->getDefaultState())->toBe(BrandStatus::Active)
        ->and($status->getOptions())->toHaveKeys([
            BrandStatus::Draft->value,
            BrandStatus::Active->value,
            BrandStatus::Inactive->value,
            BrandStatus::Discontinued->value,
        ]);
});
