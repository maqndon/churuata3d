<?php

namespace Tests\Feature\models;

use Tests\TestCase;
use App\Models\File;
use App\Models\User;
use App\Models\Image;
use App\Models\Licence;
use Tests\Traits\ProductSetUpTrait;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductTest extends TestCase
{
    use RefreshDatabase, WithFaker, ProductSetUpTrait;

    /** @test */
    public function products_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('products', [
            'id',
            'created_by',
            'licence_id',
            'title',
            'slug',
            'sku',
            'excerpt',
            'body',
            'stock',
            'price',
            'sale_price',
            'status',
            'is_featured',
            'is_downloadable',
            'is_free',
            'is_printable',
            'is_parametric',
            'related_parametric',
            'downloads',
        ]), 1);
    }

    /** @test */
    public function model_product_exist()
    {
        $this->assertModelExists($this->product);
    }

    /** @test */
    public function a_product_belongs_to_a_user()
    {
        $this->assertInstanceOf(User::class, $this->product->user);
    }

    /** @test */
    public function a_product_belongs_to_many_categories()
    {
        $this->assertInstanceOf(Collection::class, $this->product->categories);
    }

    /** @test */
    public function a_product_has_a_licence()
    {
        $this->assertInstanceOf(Licence::class, $this->product->licence);
    }

    /** @test */
    public function a_product_belongs_to_many_tags()
    {
        $this->assertInstanceOf(Collection::class, $this->product->tags);
    }

    /** @test */
    public function a_product_morphs_one_image()
    {
        $this->assertInstanceOf(Image::class, $this->product->images);
    }

    /** @test */
    public function a_product_morphs_one_file()
    {
        $this->assertInstanceOf(File::class, $this->product->files);
    }
}
