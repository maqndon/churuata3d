<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $tags = new TagResource(Tag::paginate($perPage));
            return TagResource::collection($tags);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'No Tag found'
            ], 404);
        }
    }

    public function show(string $id)
    {
        try {
            $tag = Tag::findOrFail($id);
            return new TagResource($tag);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Tag not found'
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
            'name' => 'required|string|unique:tags,name',
            'slug' => 'required|alpha_dash|unique:tags,slug',
        ]);

        // Handle validation failure
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        try {
            Tag::create([
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
            ]);

            return response()->json([
                'message' => 'Tag ' . $request->input('name') . ' Created Successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            // \Log::error('Error creating Tag: ' . $th->getMessage());
            return response()->json([
                'message' => 'Tag could not be Created Successfully',
            ], 500);
        }
    }
}
