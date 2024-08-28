<?php

namespace Tests\Feature;

use App\Models\Tag;
use Tests\TestCase;
use Tests\Traits\ProductTrait;
use Tests\Traits\CategoryTrait;
use Tests\Traits\MostDownloadedTrait;
use Database\Factories\SiteSettingFactory;
use Database\Factories\SocialMediaFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RouteTest extends TestCase
{
    use RefreshDatabase, CategoryTrait, MostDownloadedTrait, ProductTrait;

    /** @test */
    public function home_route(): void
    {
        $product = $this->createProduct();
        $categorySlug = $product->categories()->first();
        $tagSlug = $product->tags()->first();

        $siteSettings = SiteSettingFactory::new()->create();
        $socialMedias = SocialMediaFactory::new()->count(3)->create();

        $response = $this->get(route('welcome'), [
            'mostDownloadedProducts' => $this->mostDownloaded(),
            'category_slug' => $categorySlug,
            'tag_slug' => $tagSlug,
            'product' => $product,
            'siteSettings' => $siteSettings,
            'socialMedias' => $socialMedias
        ]);

        $response-> assertOk();
    }

    /** @test */
    public function products_route(): void
    {
        $response = $this->get(route('products.index'));

        $response-> assertOk();
    }

    /** @test */
    public function categories_index_route(): void
    {

        $response = $this->get(route('products.categories'));

        $response-> assertOk();
    }

    /** @test */
    public function tags_index_route(): void
    {
        $response = $this->get(route('tags.index'));

        $response-> assertOk();
    }

    /** @test */
    public function product_category_show_route(): void
    {
        $category = $this->createCategory();

        $response = $this->get(route('categories.show', ['category_slug' => $category->slug]), [
            'mostDownloadedProducts' => $this->mostDownloaded()
        ]);

        $response->assertOk();
    }

    /** @test */
    public function product_tag_show_route(): void
    {
        $tag = Tag::factory()->create();

        $response = $this->get(route('tags.show', ['tag_slug' => $tag->slug]));

        $response->assertOk();
    }

    // /** @test */
    // public function product_category_showByCategory_route(): void
    // {
    //     $user = User::factory()->create();

    //     $this->actingAs($user);

    //     $product = Product::factory()->make();
    //     $product->created_by = $user->id;
    //     $product->save();

    //     $category = Category::factory()->create();

    //     $image = Image::factory()->make();
    //     $image->imageable_id = $product->id;
    //     $image->save();

    //     $bom = Bom::factory()->make();
    //     $bom->bomable_id = $product->id;
    //     $bom->save();

    //     $product->categories()->save($category, ['categorizable_type' => Product::class]);
    //     $product->images()->save($image, ['imageable_type' => Product::class]);
    //     $product->bill_of_materials()->save($bom, ['bomable_type' => Product::class]);

    //     $url = route('products.showByCategory', ['category_slug' => $category->slug, 'product_slug' => $product->slug]);

    //     $response = $this->get($url);
    //     $response-> assertOk();
    // }

    // /** @test */
    // public function product_category_showByTag_route(): void
    // {

    //     $user = User::factory()->create();
    //     // $this->actingAs($user);

    //     $tag = Tag::factory()->create();

    //     $product = Product::factory()->make();
    //     $product->created_by = $user->id;
    //     $product->save();
    //     $product->tags()->attach($tag, ['tagable_id' => $product->id, 'tagable_type' => Product::class]);

    //     $response = $this->get(route('products.showByTag', ['tag_slug' => $tag->slug, 'product_slug' => $product->slug]));

    //     $response-> assertOk();
    // }
}
