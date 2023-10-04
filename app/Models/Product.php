<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'excerpt', 'slug', 'categories', 'tags', 'seo_title', 'meta_description', 'meta_keywords', 'image', 'image_gallery', 'status', 'files', 'price', 'sale_price', 'downloadable'];

    protected $guarded = ['creator_id'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'product_tags');
    }

    public function getImageBrowseAttribute()
    {
        return $this->image ?? 'no_image.svg';
    }

    public function sales()
    {
        return $this->hasOne(Sale::class);
    }

    public function product_downloads()
    {
        return $this->hasOne(ProductDownload::class);
    }

    public function print_settings()
    {
        return $this->belongsToMany(PrintSetting::class, 'product_print_settings');
    }

    public function printing_materials()
    {
        return $this->belongsToMany(PrintingMaterial::class, 'product_printing_materials');
    }

    public function print_supports_rafts()
    {
        return $this->hasOne(PrintSupportRaft::class);
    }

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }

    public function bill_of_materials()
    {
        return $this->hasMany(ProductBillOfMaterial::class);
    }

    public function created_by()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function product_seo()
    {
        return $this->hasOne(ProductSeo::class);
    }
}
