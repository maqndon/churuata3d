<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\Products\StoreProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;

class ProductController extends Controller
{
    private $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $products = new ProductResource(Product::paginate($perPage));

            return ProductResource::collection($products);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'No products found'
            ], 404);
        }
    }

    public function show(Product $product)
    {
        try {
            return new ProductResource($product);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function store(StoreProductRequest $request)
    {
        try {
            $this->productService->storeProduct($request->all());

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

    public function update(UpdateProductRequest $request, Product $product)
    {
        try {
            $product = $this->productService->updateProduct($product, $request->all());

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

    public function destroy(Product $product)
    {
        try {
            $this->productService->destroyProduct($product);

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
