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

    protected $fillable = ['title', 'body', 'excerpt', 'slug', 'categories', 'tags', 'seo_title', 'meta_description', 'meta_keywords', 'image', 'image_gallery', 'status', 'files', 'price', 'sale_price', 'downloadable'];

    protected $guarded = ['creator_id'];

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function getImageBrowseAttribute()
    {
        return $this->image ?? 'no_image.svg';
    }

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function product_downloads(): HasOne
    {
        return $this->hasOne(ProductDownload::class);
    }

    public function print_settings(): BelongsToMany
    {
        return $this->belongsToMany(PrintSetting::class, 'product_print_settings');
    }

    public function printing_materials(): BelongsToMany
    {
        return $this->belongsToMany(PrintingMaterial::class, 'product_printing_materials');
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
