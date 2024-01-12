<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Str;
use App\Services\ZipDownloadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Collection;

class ProductController extends Controller
{

    protected $zipDownloadService;

    public function __construct(ZipDownloadService $zipDownloadService)
    {
        $this->zipDownloadService = $zipDownloadService;
    }

    public function show(Request $request, $slug)
    {

        //product
        $product = Product::where('slug', $slug)->first();

        //products printing material(s)
        $printingMaterials = $product->printing_materials()->pluck('name')->toArray();

        // dd($printing_materials);

        //product print settings
        $printSettings = $product->print_settings()->get();

        //bill of materials
        $bom = $product->bill_of_materials()->get();
        $billOfMaterials = count($bom) != 0  ? $bom->pluck('item') : false;

        //product licence
        $licence = $product->licence()->first();

        //product tags
        $tagsArray = $product->tags()->get();
        $tags = $tagsArray->pluck('slug');

        //product categories
        $categoriesArray = $product->categories()->get();
        $categories = $categoriesArray->pluck('name');

        //common contents, for "all" products the same content
        // $common_content = DB::Table('product_common_content')->get();
        // $common_contents = [];

        // foreach ($common_content as $content) {
        //     $common_contents[$content->type] = $content->content;
        // }

        //related products

        return view('products.show', compact(
            'product',
            // 'downloads',
            'printSettings',
            'printingMaterials',
            'billOfMaterials',
            'licence',
            'tags',
            'categories',
            // 'common_contents'
        ));
    }

    public function is_free($record)
    {
        dd(Product::find($record->id));
    }

    public function downloadProductFiles($productId)
    {
        // Find the product by ID
        $product = Product::findOrFail($productId);

        // Get the files associated with the product
        $files = $product->files->files_names;

        // Get the slug
        $slug = Str::slug($product->title);

        // Set the zip name
        $zipFileName = 'files_' . $slug . '.zip';

        // Use the service to download files in a zip
        return $this->zipDownloadService->downloadFilesInZip($files, $zipFileName, $slug, 'product');
    }
}
