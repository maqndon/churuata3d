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

            // First delete stored images in the disk and then the database entries
            $images = $product->images->images_names;
            foreach ($images as $image) {
                Storage::disk('public')->delete($image);
            }

            $files = $product->files->files_names;
            foreach ($files as $file) {
                Storage::disk('local')->delete($file);
            }
            
            // delete all Polymorphic Relationships
            if ($product->images) {
                $product->images->delete();
            }

            if ($product->files) {
                $product->files->delete();
            }

            if ($product->bill_of_materials) {
                foreach($product->bill_of_materials as $bom){
                    $bom->delete();
                }
            }

            if ($product->categories) {
                foreach($product->categories as $category){
                    $category->delete();
                }
            }

            if ($product->tags) {
                foreach($product->tags as $tag){
                    $tag->delete();
                }
            }

            if ($product->seos) {
                $product->seos->delete();
            }
        });
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
        return $this->morphToMany(Category::class, 'categoryable');
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

    public function licence(): BelongsTo
    {
        return $this->belongsTo(Licence::class);
    }

    public function bill_of_materials(): MorphMany
    {
        return $this->morphMany(Bom::class, 'bomable');
    }

    public function created_by(): BelongsTo
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
