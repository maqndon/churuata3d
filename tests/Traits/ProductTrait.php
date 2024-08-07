<?php

namespace Tests\Traits;

use App\Models\Bom;
use App\Models\Product;

trait ProductTrait
{
    use UserTrait, CategoryTrait, TagTrait, ImageTrait, FileTrait;

    protected $product;

    public function createProduct(): Product
    {
        $user = $this->createUser();
        $category = $this->createCategory();
        $tag = $this->createTag();
        
        $product = Product::factory()->make();
        $product->created_by = $user->id;
        $product->save();

        $image = $this->makeImage();
        $image->imageable_id = $product->id;
        $image->save();

        $file = $this->makeFile();
        $file->fileable_id = $product->id;
        $file->save();

        $bom = Bom::factory()->make();
        $bom->bomable_id = $product->id;
        $bom->save();

        $product->tags()->save($tag, ['tagable_type' => Product::class]);
        $product->categories()->save($category, ['categorizable_type' => Product::class]);
        $product->images()->save($image, ['imageable_type' => Product::class]);
        $product->files()->save($file, ['fileable_type' => Product::class]);
        $product->bill_of_materials()->save($bom, ['bomable_type' => Product::class]);
        
        $product->load('categories', 'tags', 'images', 'files', 'bill_of_materials');

        return $product;
    }

}
