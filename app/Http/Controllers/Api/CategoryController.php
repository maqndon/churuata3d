<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
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
            $category = Category::find($id);
            return new CategoryResource($category);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        // Create a validator instance
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|unique:categories,name',
            'slug' => 'required|alpha_dash|unique:categories,slug',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            Category::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
            ]);

            return response()->json([
                'message' => 'Category ' . $request->input('name') . ' Created Successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            // \Log::error('Error creating category: ' . $th->getMessage());
            return response()->json([
                'message' => 'Category could not be Created Successfully',
            ], 500);
        }
    }
}
