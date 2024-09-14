<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;
use App\Repositories\TagRepositoryInterface;

class TagController extends Controller
{
    private $tagRepository;

    public function __construct(TagRepositoryInterface $tagRepository)
    {
        $this->tagRepository = $tagRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $tags = new TagResource(Tag::paginate($perPage));
            return TagResource::collection($tags);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
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
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {
            return response()->json([
                'message' => 'Tag not found'
            ], 404);
        }
    }

    public function getTags(Product $product)
    {
        return response()->json([
            'tags' => TagResource::collection($product->tags)
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTagRequest $request)
    {
        try {
            Tag::create($request->only([
                'name',
                'slug',
            ]));

            return response()->json([
                'message' => 'Tag ' . $request->input('name') . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error creating Tag: ' . $th->getMessage());
            return response()->json([
                'message' => 'Tag could not be created successfully',
            ], 500);
        }
    }

    public function attachTag(Request $request, Product $product, Tag $tag)
    {
        try {
            $this->tagRepository->attachTag($request, $product, $tag);
            return response()->json([
                'message' => 'Tag ' . $tag->name . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error adding Tag: ' . $th->getMessage());
            return response()->json([
                'message' => 'Tag could not be added successfully',
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            // Filter the data that has changed
            $data = array_filter($request->only([
                'name',
                'slug',
            ]), function ($value) {
                return !is_null($value);
            });

            // Update the tag with the filtered data
            $tag->update($data);

            return response()->json([
                'message' => 'Tag ' . $tag->name . ' updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error updating tag: ' . $th->getMessage());

            return response()->json([
                'message' => 'Tag could not be updated successfully',
            ], 500);
        }
    }

    public function destroy(Tag $tag)
    {
        try {
            $tag->delete();
            return response()->json([
                'message' => 'Tag ' . $tag->name . ' deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error deleting tag: ' . $th->getMessage());

            return response()->json([
                'message' => 'Tag could not be deleted successfully',
            ], 500);
        }
    }

    public function detachTag(Request $request, Product $product, Tag $tag)
    {
        try {
            $this->tagRepository->detachTag($request, $product, $tag);
            return response()->json([
                'message' => 'Tag ' . $tag->name . ' removed from ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error removing Tag: ' . $th->getMessage());
            return response()->json([
                'message' => 'Tag could not be removed successfully',
            ], 500);
        }
    }
}
