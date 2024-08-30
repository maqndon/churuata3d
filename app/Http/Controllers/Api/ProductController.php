<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            # load default relationships from Scope's Product Model
            $products = Product::withDefaultRelationships()->paginate($perPage);
            return ProductResource::collection($products);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No products found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'created_by' => 'required|numeric',
            'licence_id' => 'required|numeric',
            'title' => 'required|unique:products|max:255',
            'slug' => 'required|unique:products|max:255',
            'sku' => 'required|unique:products|max:255',
            'excerpt' => 'required|string',
            'body' => 'required|string',
            'stock' => 'numeric',
            'price' => 'exclude_unless:is_free,false|required|numeric',
            'sale_price' => 'exclude_unless:is_free,false|required|numeric',
            'status' => 'required',
            'is_featured' => 'required|boolean',
            'is_downloadable' => 'required|boolean',
            'is_free' => 'required|boolean',
            'is_printable' => 'required|boolean',
            'is_parametric' => 'required|boolean',
            'related_parametric' => 'string',
            'downloads' => 'numeric',
            'created_at' => 'required|date',
            'printing_materials' => 'array',
            'printing_materials.*.id' => 'numeric',
            'print_settings' => 'array',
            'print_settings.*.id' => 'numeric',
            'bill_of_materials' => 'array',
            'bill_of_materials.*.qty' => 'integer',
            'bill_of_materials.*.item' => 'string|max:255',
            'files' => 'array',
            'files.*.files_names' => 'array',
            'files.*.files_names.*' => 'string',
            'files.*.metadata' => 'string|max:255',
            'images' => 'array',
            'images.*.images_names' => 'array',
            'images.*.images_names.*' => 'string',
            'images.*.metadata' => 'string|max:255',
            'seos' => 'array',
            'seos.*.title' => 'string|unique:seos,title',
            'seos.*.meta_description.*' => 'string',
            'seos.*.meta_keywords' => 'array',
            'categories' => 'array',
            'categories.*.category_id' => 'numeric',
            'tags' => 'array',
            'tags.*.tag_id' => 'numeric'
        ]);

        try {
            $product = Product::create([
                'created_by' => $request->input('user'),
                'licence_id' => $request->input('licence_id'),
                'title' => $request->input('title'),
                'slug' => $request->input('slug'),
                'sku' => $request->input('sku'),
                'excerpt' => $request->input('excerpt'),
                'body' => $request->input('body'),
                'stock' => $request->input('stock'),
                'price' => $request->input('price'),
                'sale_price' => $request->input('sale_price'),
                'status' => $request->input('status'),
                'is_featured' => $request->input('is_featured'),
                'is_downloadable' => $request->input('is_downloadable'),
                'is_free' => $request->input('is_free'),
                'is_printable' => $request->input('is_printable'),
                'is_parametric' => $request->input('is_parametric'),
                'related_parametric' => $request->input('related_parametric'),
                'downloads' => $request->input('downloads'),
                'created_at' => $request->input('created_at'),
            ]);

            // add printing materials
            if ($request->printing_materials) {
                foreach ($request->input('print_settings') as $print_setting) {
                    $product->print_settings()->attach($print_setting);
                }
            }

            // add print settings
            if ($request->printing_materials) {
                foreach ($request->input('printing_materials') as $printing_material) {
                    $product->printing_materials()->attach($printing_material);
                }
            }

            // add bill of materials
            if ($request->bill_of_materials) {
                foreach ($request->input('bill_of_materials') as $bom) {
                    $product->bill_of_materials()->create($bom);
                }
            }

            // add product files
            if ($request->input('files')) {
                foreach ($request->input('files') as $fileData) {
                    $product->files()->create($fileData);
                }
            }

            // add product images
            if ($request->input('images')) {
                foreach ($request->input('images') as $image) {
                    $product->images()->create($image);
                }
            }

            // add product seo
            if ($request->input('seos')) {
                foreach ($request->input('seos') as $seo) {
                    $product->seos()->create($seo);
                }
            }

            // add product categories
            if ($request->input('categories')) {
                foreach ($request->input('categories') as $category) {
                    $product->categories()->sync($category);
                }
            }

            // add product tags
            if ($request->input('tags')) {
                foreach ($request->input('categories') as $tag) {
                    $product->tags()->sync($tag);
                }
            }

            return response()->json([
                'message' => 'Product ' . $request->title . ' Created Successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating product: ' . $th->getMessage());

            return response()->json([
                'message' => 'Product could not be Created Successfully',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::withDefaultRelationships()->findOrFail($id);
            return new ProductResource($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
