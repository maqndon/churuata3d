<?php

namespace Tests\Feature\database;

use Tests\TestCase;
use Illuminate\Support\Facades\Schema;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    /** @test */
    public function users_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('users', [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token'
        ]), 1);
    }

    /** @test */
    public function boms_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('boms', [
            'bomable_type',
            'bomable_id',
            'qty',
            'item',
        ]), 1);
    }

    /** @test */
    public function categories_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('categories', [
            'id',
            'name',
            'slug',
        ]), 1);
    }

    /** @test */
    public function categorizables_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('categorizables', [
            'categorizable_type',
            'categorizable_id',
            'category_id',
        ]), 1);
    }

    /** @test */
    public function comments_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('comments', [
            'commentable_type',
            'commentable_id',
            'comment',
        ]), 1);
    }

    /** @test */
    public function files_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('files', [
            'fileable_type',
            'fileable_id',
            'files_names',
            'metadata',
        ]), 1);
    }

    /** @test */
    public function images_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('images', [
            'imageable_type',
            'imageable_id',
            'images_names',
            'metadata',
        ]), 1);
    }

    /** @test */
    public function licences_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('licences', [
            'id',
            'name',
            'short_description',
            'description',
            'link',
            'icon',
            'logo',
        ]), 1);
    }

    /** @test */
    public function posts_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('posts', [
            'id',
            'author_id',
            'title',
            'excerpt',
            'body',
            'slug',
            'is_featured',
            'status',
            'related_product',
        ]), 1);
    }

    /** @test */
    public function printing_materials_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('printing_materials', [
            'id',
            'name',
            'nozzle_size',
            'min_hot_bed_temp',
            'max_hot_bed_temp',
        ]), 1);
    }

    /** @test */
    public function printing_materials_product_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('printing_material_product', [
            'product_id',
            'printing_material_id',
        ]), 1);
    }

    /** @test */
    public function print_settings_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('print_settings', [
            'id',
            'print_strength',
            'resolution',
            'infill',
            'top_layers',
            'bottom_layers',
            'walls',
            'speed',
            'description',
        ]), 1);
    }

    /** @test */
    public function print_setting_product_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('print_setting_product', [
            'product_id',
            'print_setting_id',
        ]), 1);
    }

    /** @test */
    public function print_support_rafts_table_has_expected_columns()
    {
        $this->assertTrue(Schema::hasColumns('print_support_rafts', [
            'product_id',
            'has_supports',
            'has_raft',
        ]), 1);
    }

    // /** @test */
    // public function products_table_has_expected_columns(): void
    // {
    //     $this->assertTrue(Schema::hasColumns('products', [
    //         'id',
    //         'created_by',
    //         'licence_id',
    //         'title',
    //         'slug',
    //         'sku',
    //         'excerpt',
    //         'body',
    //         'stock',
    //         'price',
    //         'sale_price',
    //         'status',
    //         'is_featured',
    //         'is_downloadable',
    //         'is_free',
    //         'is_printable',
    //         'is_parametric',
    //         'related_parametric',
    //         'downloads',
    //     ]), 1);
    // }

    /** @test */
    public function product_common_contents_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('product_common_contents', [
            'id',
            'type',
            'content',
        ]), 1);
    }

    /** @test */
    public function product_physical_attributes_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('product_physical_attributes', [
            'product_id',
            'weight',
            'length',
            'width',
            'height',
        ]), 1);
    }

    /** @test */
    public function sales_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('sales', [
            'product_id',
            'quantity',
            'amount',
        ]), 1);
    }

    /** @test */
    public function seos_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('seos', [
            'seoable_type',
            'seoable_id',
            'title',
            'meta_description',
            'meta_keywords',
        ]), 1);
    }

    /** @test */
    public function site_settings_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('site_settings', [
            'company_name',
            'site_title',
            'site_description',
            'site_logo',
            'site_google_analytics_tracking_id',
        ]), 1);
    }

    /** @test */
    public function site_setting_social_media_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('site_setting_social_media', [
            'site_setting_id',
            'social_media_id',
            'url',
        ]), 1);
    }

    /** @test */
    public function social_media_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('social_media', [
            'id',
            'name',
            'icon',
        ]), 1);
    }

    /** @test */
    public function tags_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('tags', [
            'id',
            'name',
            'slug',
        ]), 1);
    }

    /** @test */
    public function tagables_table_has_expected_columns(): void
    {
        $this->assertTrue(Schema::hasColumns('tagables', [
            'tagable_type',
            'tagable_id',
            'tag_id',
        ]), 1);
    }
   
}
