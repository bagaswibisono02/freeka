<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categorie extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'parent_id',
        'order',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    /**
     * Get the parent Categorie
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Categorie::class, 'parent_id');
    }

    /**
     * Get the children categories
     */
    public function children(): HasMany
    {
        return $this->hasMany(Categorie::class, 'parent_id')->orderBy('order');
    }

    /**
     * Scope active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope main categories (no parent)
     */
    public function scopeMain($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope sub categories (has parent)
     */
    public function scopeSub($query)
    {
        return $query->whereNotNull('parent_id');
    }

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute()
    {
        return $this->image ? Storage::disk('public')->url($this->image) : null;
    }

    /**
     * Check if Categorie has children
     */
    public function getHasChildrenAttribute()
    {
        return $this->children()->exists();
    }

    /**
     * Get full Categorie path (breadcrumb)
     */
    public function getFullPathAttribute()
    {
        $path = [];
        $Categorie = $this;
        
        while ($Categorie) {
            $path[] = $Categorie->name;
            $Categorie = $Categorie->parent;
        }
        
        return implode(' > ', array_reverse($path));
    }
}