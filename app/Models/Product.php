<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'category_id',
        'brand_id',
        'base_price',
        'sale_price',
        'cost_price',
        'sku',
        'stock_quantity',
        'low_stock_threshold',
        'weight',
        'dimensions',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'is_active',
        'is_featured',
        'is_draft',
        'published_at',
        'main_image',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
        'stock_quantity' => 'integer',
        'low_stock_threshold' => 'integer',
        'weight' => 'decimal:2',
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'is_draft' => 'boolean',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function category()
    {
        return $this->belongsTo(Categorie::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function variants()
    {
        return $this->hasMany(Variant::class);
    }

    public function buyLinks()
    {
        return $this->hasMany(ProductBuyLink::class);
    }

    public function media()
    {
        return $this->hasMany(ProductMedia::class);
    }

    // Accessors
    public function getMainImageUrlAttribute()
    {
        return $this->main_image ? Storage::url($this->main_image) : 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80';
    }

    public function getHasDiscountAttribute()
    {
        return $this->sale_price && $this->sale_price < $this->base_price;
    }

    public function getDiscountPercentageAttribute()
    {
        if (!$this->has_discount) return 0;
        
        return round((($this->base_price - $this->sale_price) / $this->base_price) * 100);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublished($query)
    {
        return $query->where('is_draft', false)
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }
}