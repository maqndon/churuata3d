<?php

namespace App\Console\Commands;

use App\Models\Bom;
use App\Models\Category;
use App\Models\Seo;
use App\Models\Tag;
use App\Models\Sale;
use App\Models\User;
use App\Models\Licence;
use App\Models\PrintingMaterial;
use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\PrintSupportRaft;
use Illuminate\Support\Facades\DB;

class ImportWoocommerceProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:woocommerce';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products from Woocommerce XML';

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Path to your XML file
        $xmlFilePath = 'public\dummyProducts.xml';

        // Load the XML file
        $xml = simplexml_load_file($xmlFilePath);

        //Model
        $model = 'App\Models\Product';

        // Iterate over each product in the XML
        foreach ($xml->children() as $productData) {
            // Extract product attributes from XML
            $title = (string) $productData->title;
            $slug = (string) $productData->post_name;
            $sku = (string) $productData->post_name;
            $stock = (int) $productData->stock;
            $creator = (string) $productData->creator;
            // Find the user by name and associate the product with them
            $creator_user = User::where('name', $creator)->first();
            $creator_id = $creator_user->id;

            // if the product is featured 
            if ($productData->xpath('category[@domain="product_visibility"]')) {
                $featured = (string)$productData->xpath('category[@domain="product_visibility"]')[0];
            }

            $is_featured = $featured === 'featured' ? 1 : 0;

            $status = (string) $productData->status;

            // Store the HTML content as CDATA in the database
            $body = $productData->content->asXML();

            // Remove <content> tags using regex
            $body = preg_replace('/<content>(.*?)<\/content>/s', '$1', $body);

            $excerpt = $productData->excerpt->asXML();

            // Remove <excerpt> tags using regex
            $excerpt = preg_replace('/<excerpt>(.*?)<\/excerpt>/s', '$1', $excerpt);

            $images = explode(',', (string) $productData->images);

            $materials = $productData->Print_Settings->materials;
            $settings = $productData->Print_Settings->settings;
            $supports = $productData->Print_Settings->supports;
            $raft = $productData->Print_Settings->raft;
            $is_parametric = $productData->is_parametric ? (bool)$productData->is_parametric : false;
            $related_parametric = $productData->related_parametric;

            // Product licence
            $licence = $productData->licence;
            $licence_id = Licence::where('name', $licence)->value('id');

            // Extract Data inside the postmeta tags

            // $metaDescription = null;
            // $seoTitle = null;
            // price = null;
            // $salePrice = null;
            // $is_downloadable = null;

            foreach ($productData->postmeta as $meta) {
                $metaKey = (string) $meta->meta_key;
                $metaValue = (string) $meta->meta_value;

                if ($metaKey === 'price') {
                    $price = $metaValue ? $metaValue : null;
                }

                if ($metaKey === 'sale_price') {
                    $salePrice = $metaValue ? $metaValue : null;
                }

                if ($metaKey === 'downloadable') {
                    switch ($metaValue) {
                        case 'yes':
                            $is_downloadable = true;
                            break;
                        case 'no':
                            $is_downloadable = false;
                            break;
                        default:
                            $is_downloadable = true;
                            break;
                    }
                }

                if ($metaKey === 'meta_description') {
                    $metaDescription = $metaValue;
                }

                if ($metaKey === 'focus_keyword') {
                    $metaKeywords = $metaValue;
                }

                if ($metaKey === 'seo_title') {
                    $seoTitle = $metaValue;
                }

                if ($metaKey === 'downloads') {
                    $downloads = $metaValue;
                }
            }

            // Create a new product entry
            $newProduct = new Product();
            $newProduct->created_by = $creator_id;
            $newProduct->licence_id = $licence_id;
            $newProduct->title = $title;
            $newProduct->slug = $slug;
            $newProduct->sku = $sku;
            $newProduct->excerpt = $excerpt;
            $newProduct->body = $body;
            $newProduct->stock = $stock;
            $newProduct->price = $price;
            $newProduct->sale_price = $salePrice;
            $newProduct->status = $status;
            $newProduct->is_featured = $is_featured;
            $newProduct->is_downloadable = $is_downloadable;
            $newProduct->is_parametric = $is_parametric;
            $newProduct->related_parametric = $related_parametric;
            $newProduct->downloads = $downloads;
            $newProduct->save();

            // $product = Product::where('slug', $newProduct->slug)->first();
            // dd($product->id);
            // $product->bill_of_materials()->save($product, $productData->bill_of_materials);

            // Product's Bill of Materials (BOM)
            $bill_of_materials = $productData->bill_of_materials;

            if ($bill_of_materials) {

                // Create an array to store unique BOM entries
                $dataBom = [];

                foreach ($bill_of_materials->li as $bom) {
                    $item = (string)$bom;

                    // Check if the item is not already in $dataBom
                    if (!in_array($item, $dataBom)) {
                        $dataBom[] = $item;

                        // Insert data into the table
                        $newBom = new Bom();
                        $newBom->bomable_id = $newProduct->id;
                        $newBom->bomable_type = $model;
                        $newBom->qty = null;
                        $newBom->item = $item;
                        $newBom->save();
                    }
                }
            }

            if ($materials) {
                // Product printing materials
                $this->storePivot($newProduct->id, 'printing_material_id', 'name', $materials, 'printing_material_product', 'printing_materials');
            }

            // Product printing settings
            $this->storePivot($newProduct->id, 'print_setting_id', 'print_strength', $settings, 'print_setting_product', 'print_settings');

            // Product supports raft
            $supportRaft = new PrintSupportRaft();
            $supportRaft->product_id = $newProduct->id;
            $supportRaft->has_supports = (bool)$supports;
            $supportRaft->has_raft = (bool)$raft;
            $supportRaft->save();

            $this->info("Product imported: $title");

            // Assign tags to the product
            foreach ($productData->category as $category) {
                $domain = (string) $category['domain'];
                $categoryName = (string) $category;

                if ($domain === 'product_tag') {
                    $tag = Tag::firstOrCreate([
                        'name' => $categoryName,
                        'slug' => Str::of($categoryName)->slug(),
                    ]);

                    if ($tag) {
                        // Attach the existing tag to the product
                        $newProduct->tags()->attach($tag->id);
                    }
                }
            }

            // Attach existing categories with domain "product_cat"
            foreach ($productData->category as $category) {
                $domain = (string) $category['domain'];
                $categoryName = (string) $category;

                if ($domain === 'product_cat') {
                    // Find or create the category by name
                    $categoryId = Category::where('name', $categoryName)->value('id');;

                    if (!$categoryId) {
                        // Category doesn't exist, create it
                        $category = new Category();
                        $category->name = $categoryName;
                        $category->save();
                        $categoryId = $category->id;
                    }

                    // Attach the product category to the product
                    $newProduct->categories()->attach($categoryId);
                }
            }

            // Create SEO entry for the product
            $seo = new Seo();
            $seo->seoable_type = $model;
            $seo->seoable_id = $newProduct->id;
            $seo->title = $seoTitle != null ? $seoTitle : '';
            $seo->meta_description = $metaDescription != null ? $metaDescription : '';
            $seo->meta_keywords = $metaKeywords != null ? $metaKeywords : '';
            $seo->save();


            // Handle product images
            //     foreach ($images as $imageUrl) {
            //         // Download the image
            //         $imageName = basename($imageUrl);
            //         $imageContents = file_get_contents($imageUrl);
            //         $storagePath = 'products/' . $imageName;
            //         Storage::disk('public')->put($storagePath, $imageContents);

            //         // Save the image path
            //         $newProduct->addMedia(storage_path('app/public/' . $storagePath))
            //             ->usingName($imageName)
            //             ->toMediaCollection('images');
            //     }
        }

        $this->info('Import completed.');
    }

    private function storePivot($product_id, $foreign_id, $foreign_column, $data, $pivot_table, $table_foreign_id)
    {

        // Split the string into an array
        $items = explode(',', $data);

        // Create an array of data
        $dataArray = [];

        foreach ($items as $item) {

            // Perform a lookup to get the id based on the name
            $colum_name_id = DB::table($table_foreign_id)->where($foreign_column, $item)->value('id');

            $dataArray[] = [
                'product_id' => $product_id,
                $foreign_id => $colum_name_id,
            ];

            // Insert data into the table or ignore it if already exist 
            DB::table($pivot_table)->insertOrIgnore($dataArray);
        }
    }
}
