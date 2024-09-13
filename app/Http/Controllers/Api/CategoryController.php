<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Repositories\CategoryRepositoryInterface;
use App\Http\Requests\Categories\StoreCategoryRequest;
use App\Http\Requests\Categories\UpdateCategoryRequest;

class CategoryController extends Controller
{
    private $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $categories = new CategoryResource(Category::paginate($perPage));
            return CategoryResource::collection($categories);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No Category found'
            ], 404);
        }
    }

    public function show(string $id)
    {
        try {
            $category = Category::findOrFail($id);
            return new CategoryResource($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            Category::create($request->only([
                'name',
                'slug',
            ]));

            return response()->json([
                'message' => 'Category ' . $request->input('name') . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error creating Category: ' . $th->getMessage());
            return response()->json([
                'message' => 'Category could not be created successfully',
            ], 500);
        }
    }

    public function attachCategory(Request $request, Product $product, Category $category)
    {
        try {
            $this->categoryRepository->attachCategory($request, $product, $category);
            return response()->json([
                'message' => 'Category ' . $category->name . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error adding Category: ' . $th->getMessage());
            return response()->json([
                'message' => 'Category could not be added successfully',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            // Filter the data that has changed
            $data = array_filter($request->only([
                'name',
                'slug',
            ]), function ($value) {
                return !is_null($value);
            });

            // Update the category with the filtered data
            $category->update($data);

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
            $category->delete();
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

    public function detachCategory(Request $request, Product $product, Category $category)
    {
        try {
            $this->categoryRepository->detachCategory($request, $product, $category);
            return response()->json([
                'message' => 'Category ' . $category->name . ' removed from ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error adding Category: ' . $th->getMessage());
            return response()->json([
                'message' => 'Category could not be removed successfully',
            ], 500);
        }
    }
}
