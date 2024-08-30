<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;

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
    public function store(StoreProductRequest $request)
    {

        try {
            $product = Product::create($request->only([
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
                'created_at'
            ]));

            if ($request->has('printing_materials')) {
                $product->printing_materials()->sync($request->input('printing_materials.*.id'));
            }

            if ($request->has('print_settings')) {
                $product->print_settings()->sync($request->input('print_settings.*.id'));
            }

            if ($request->has('bill_of_materials')) {
                $product->bill_of_materials()->createMany($request->input('bill_of_materials'));
            }

            if ($request->has('files')) {
                $product->files()->createMany($request->input('files'));
            }

            if ($request->has('images')) {
                $product->images()->createMany($request->input('images'));
            }

            if ($request->has('seos')) {
                $product->seos()->createMany($request->input('seos'));
            }

            if ($request->has('categories')) {
                $product->categories()->sync($request->input('categories.*.category_id'));
            }

            if ($request->has('tags')) {
                $product->tags()->sync($request->input('tags.*.tag_id'));
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
