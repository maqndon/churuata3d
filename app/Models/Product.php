<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'slug',
        'categories',
        'tags',
        'seo_title',
        'meta_description',
        'meta_keywords',
        'image',
        'image_gallery',
        'status',
        'files',
        'price',
        'sale_price',
        'downloadable',
        'downloads'
    ];

    protected $cast = [
        'is_featured' => 'boolean',
        'is_virtual' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_printable' => 'boolean',
        'is_parametric' => 'boolean',
        'tags' => 'array',
        'categories' => 'array',
    ];
    
    // protected $guarded = ['creator_id'];

    public function getDownloadCountAttribute()
    {
        return $this->downloads;
    }

    public function getImageBrowseAttribute()
    {
        return $this->image ?? 'no_image.svg';
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function print_settings(): BelongsToMany
    {
        return $this->belongsToMany(PrintSetting::class);
    }

    public function printing_materials(): BelongsToMany
    {
        return $this->belongsToMany(PrintingMaterial::class);
    }

    public function print_supports_rafts(): HasOne
    {
        return $this->hasOne(PrintSupportRaft::class);
    }

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    public function bill_of_materials(): HasMany
    {
        return $this->hasMany(ProductBillOfMaterial::class);
    }

    public function created_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function seos(): MorphMany
    {
        return $this->morphMany(Seo::class, 'seoable');
    }
}
