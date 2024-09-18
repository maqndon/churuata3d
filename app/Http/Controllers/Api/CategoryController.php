<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CategoryService;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;

class CategoryController extends Controller
{
    private $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $categories = new CategoryResource(Category::paginate($perPage));

            return CategoryResource::collection($categories);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'No Category found'
            ], 404);
        }
    }

    public function show(Category $category)
    {
        try {
            return new CategoryResource($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
    }

    public function getCategories(Product $product)
    {
        return response()->json([
            'categories' => CategoryResource::collection($product->categories)
        ], 200);
    }

    public function store(StoreCategoryRequest $request)
    {
        try {
            $this->categoryService->storeCategory($request->all());

            return response()->json([
                'message' => 'Category ' . $request->input('name') . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating category: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category could not be created successfully',
            ], 500);
        }
    }

    public function attachCategory(Request $request, Product $product, Category $category)
    {
        try {
            $this->categoryService->attachCategory($product, $category);

            return response()->json([
                'message' => 'Category ' . $category->name . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error attaching category: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category could not be attached successfully',
            ], 500);
        }
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $this->categoryService->updateCategory($category, $request->all());

            return response()->json([
                'message' => 'Category ' . $category->name . ' updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error updating category: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category could not be updated successfully',
            ], 500);
        }
    }

    public function destroy(Category $category)
    {
        try {
            $this->categoryService->destroyCategory($category);

            return response()->json([
                'message' => 'Category ' . $category->name . ' deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error deleting category: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category could not be deleted successfully',
            ], 500);
        }
    }

    public function detachCategory(Product $product, Category $category)
    {
        try {
            $this->categoryService->detachCategory($product, $category);

            return response()->json([
                'message' => 'Category ' . $category->name . ' removed from ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error removing category: ' . $th->getMessage());

            return response()->json([
                'message' => 'Category could not be removed successfully',
            ], 500);
        }
    }
}
