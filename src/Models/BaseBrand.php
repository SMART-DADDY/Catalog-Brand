<?php

namespace SmartDaddy\CatalogBrand\Models;

use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use InvalidArgumentException;
use SmartDaddy\CatalogBrand\Enums\BrandStatus;

abstract class BaseBrand extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => BrandStatus::class,
        ];
    }

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany($this->resolveProductModelClass());
    }

    protected function resolveProductModelClass(): string
    {
        $modelClass = config('catalog-brand.models.product');

        if (! is_string($modelClass) || $modelClass === '') {
            throw new InvalidArgumentException('Missing required config value: catalog-brand.models.product');
        }

        return $modelClass;
    }

    public function getTotalRevenueAttribute(): float
    {
        return $this->products()
            ->with(['variations.sales'])
            ->get()
            ->sum(function ($product) {
                return $product->variations->sum(function ($variation) {
                    return $variation->sales->sum(function ($sale) {
                        $pivot = $sale->pivot;

                        return $pivot->total ?? 0;
                    });
                });
            });
    }

    public function getTotalCostAttribute(): float
    {
        return $this->products()
            ->with(['variations.sales'])
            ->get()
            ->sum(function ($product) {
                return $product->variations->sum(function ($variation) {
                    return $variation->sales->sum(function ($sale) {
                        $pivot = $sale->pivot;

                        return $pivot->supplier_total ?? 0;
                    });
                });
            });
    }

    public function getTotalProfitAttribute(): float
    {
        return $this->total_revenue - $this->total_cost;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->total_revenue == 0) {
            return 0;
        }

        return ($this->total_profit / $this->total_revenue) * 100;
    }

    public function getTotalQuantitySoldAttribute(): float
    {
        return $this->products()
            ->with(['variations.sales'])
            ->get()
            ->sum(function ($product) {
                return $product->variations->sum(function ($variation) {
                    return $variation->sales->sum('pivot.quantity');
                });
            });
    }
}
