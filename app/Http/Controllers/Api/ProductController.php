<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;

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
            Product::create($request->only([
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

            return response()->json([
                'message' => 'Product ' . $request->title . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating product: ' . $th->getMessage());

            return response()->json([
                'message' => 'Product could not be created successfully',
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
    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            // Filter the data that has changed
            $data = array_filter($request->only([
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
                'downloads'
            ]), function ($value) {
                return !is_null($value);
            });

            // Update the product with the filtered data
            $product->update($data);

            return response()->json([
                'message' => 'Product ' . $request->title . ' updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error updating product: ' . $th->getMessage());

            return response()->json([
                'message' => 'Product could not be updated successfully',
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            $product->delete();
            return response()->json([
                'message' => 'Product ' . $product->title . ' deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error deleting product: ' . $th->getMessage());

            return response()->json([
                'message' => 'Product could not be deleted successfully',
            ], 500);
        }
    }
}
