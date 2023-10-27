<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'slug',
        'sku',
        'stock',
        'licence_id',
        'status',
        'price',
        'sale_price',
        'is_featured',
        'is_downloadable',
        'is_free',
        'is_printable',
        'is_parametric',
        'related_parametric',
        'downloads'
    ];

    protected $cast = [
        'is_featured' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_printable' => 'boolean',
        'is_parametric' => 'boolean',
        'images' => 'array',
        'files' => 'array',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['created_by'] = auth()->id();
    }
    
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

    public function seos(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function images(): MorphMany
    {
        return $this->MorphMany(Image::class, 'imageable');
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable');
    }
}
