<?php

namespace Tests\Traits;

use App\Models\Product;

trait MostDownloadedTrait
{
    use UserTrait, CategoryTrait;

    protected $products;

    public function mostDownloaded()
    {

        $user = $this->createUser();

        $category = $this->createCategory();
        
        $products = Product::factory()->count(3)->make();
        
        $products->each(function ($product) use ($category, $user) {
            $product->created_by = $user->id;
            $product->save();
            $product->categories()->attach($category, ['categorizable_type' => Product::class]);

            $image = $this->makeImage();
            $image->imageable_id = $product->id;
            $image->save();

            $product->images()->save($image, ['imageable_type' => Product::class]);
        });
        
        
        return $products;
    }
}