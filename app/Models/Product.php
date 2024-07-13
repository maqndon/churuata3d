<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
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

    protected $casts = [
        'is_free' => 'boolean',
        'is_featured' => 'boolean',
        'is_downloadable' => 'boolean',
        'is_printable' => 'boolean',
        'is_parametric' => 'boolean',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->attributes['created_by'] = auth()->id();
    }

    protected static function booted(): void
    {
        self::deleting(function (Product $product) {

            // delete all Polymorphic Relationships
            if ($product->images) {
                // First delete stored images in the disk
                $imagesDir = dirname($product->images->images_names[0]);
                Storage::disk('public')->deleteDirectory($imagesDir);
                //delete database's entries 
                $product->images->delete();
            }

            if ($product->files) {
                $filesDir = dirname($product->files->files_names[0]);
                Storage::disk('local')->deleteDirectory($filesDir);
                $product->files->delete();
            }

            if ($product->bill_of_materials) {
                foreach ($product->bill_of_materials as $bom) {
                    $bom->delete();
                }
            }

            if ($product->categories) {
                foreach ($product->categories as $category) {
                    $product->categories()->detach($category->id);
                }
            }

            if ($product->tags) {
                foreach ($product->tags as $tag) {
                    $product->tags()->detach($tag->id);
                }
            }

            if ($product->seos) {
                $product->seos->delete();
            }
        });
    }

    # Scope to load default relationships
    public function scopeWithDefaultRelationships($query)
    {
        return $query->with([
            'user',
            'licence',
            'files',
            'images',
            'seos',
            'comments',
            'bill_of_materials',
            'printing_materials',
            'print_settings',
            'tags',
            'sales',
            'categories',
            'print_supports_rafts',
            'product_physical_attributes',
        ]);
    }


    public function scopePublished($query)
    {
        return $query->where('status', 'Published');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'Draft');
    }

    public function scopeIsFree($query)
    {
        return $query->where('is_free', true);
    }

    public function scopeIsPrintable($query)
    {
        return $query->where('is_printable', true);
    }

    public function scopeIsDownloadable($query)
    {
        return $query->where('is_downloadable', true);
    }

    public function scopeIsParametric($query)
    {
        return $query->where('is_parametric', true);
    }

    public function scopeIsFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->where('sale_price','>', 0)->orWhere('sale_price','=', 0);
    }

    public function scopeOnStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeMostDownlaoded($query, $limit=3)
    {
        return $query->orderBy('downloads', 'desc')->limit($limit);
    }

    public function getDownloadCountAttribute()
    {
        return $this->downloads;
    }

    public function getImageBrowseAttribute()
    {
        return $this->image ?? 'no_image.svg';
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'tagable');
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

    public function product_physical_attributes(): HasOne
    {
        return $this->hasOne(ProductPhysicalAttribute::class);
    }

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    public function bill_of_materials(): MorphMany
    {
        return $this->morphMany(Bom::class, 'bomable');
    }

    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function seos(): MorphOne
    {
        return $this->morphOne(Seo::class, 'seoable');
    }

    public function images(): MorphOne
    {
        return $this->morphOne(Image::class, 'imageable');
    }

    public function files(): MorphOne
    {
        return $this->morphOne(File::class, 'fileable');
    }
}
