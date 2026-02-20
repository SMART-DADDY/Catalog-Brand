<?php

use Filament\Support\Icons\Heroicon;
use SmartDaddy\CatalogBrand\Enums\BrandStatus;

it('returns expected labels, colors, icons and descriptions', function (): void {
    expect(BrandStatus::Draft->getLabel())->toBe('Draft')
        ->and(BrandStatus::Draft->getColor())->toBe('warning')
        ->and(BrandStatus::Draft->getIcon())->toBe(Heroicon::Clock)
        ->and(BrandStatus::Draft->getDescription())->toBe('Brand is being created or edited, not visible to customers.')
        ->and(BrandStatus::Active->getLabel())->toBe('Active')
        ->and(BrandStatus::Active->getColor())->toBe('success')
        ->and(BrandStatus::Active->getIcon())->toBe(Heroicon::CheckBadge)
        ->and(BrandStatus::Active->getDescription())->toBe('Brand is available for purchase.')
        ->and(BrandStatus::Inactive->getColor())->toBe('gray')
        ->and(BrandStatus::Inactive->getIcon())->toBe(Heroicon::NoSymbol)
        ->and(BrandStatus::Inactive->getDescription())->toBe('Brand is temporarily unavailable but not deleted.')
        ->and(BrandStatus::Discontinued->getColor())->toBe('gray')
        ->and(BrandStatus::Discontinued->getIcon())->toBe(Heroicon::NoSymbol)
        ->and(BrandStatus::Discontinued->getDescription())->toBe('Brand is permanently removed from sale.');
});

it('exposes active as default status', function (): void {
    expect(BrandStatus::default())->toBe(BrandStatus::Active);
});
