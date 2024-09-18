<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use App\Models\Product;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tags\StoreTagRequest;
use App\Http\Requests\Tags\UpdateTagRequest;

class TagController extends Controller
{
    private $tagService;

    public function __construct(TagService $tagService)
    {
        $this->tagService = $tagService;
    }

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

    public function show(Tag $tag)
    {
        try {
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

    public function store(StoreTagRequest $request)
    {
        try {
            $this->tagService->storeTag($request->all());

            return response()->json([
                'message' => 'Tag ' . $request->input('name') . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating Tag: ' . $th->getMessage());

            return response()->json([
                'message' => 'Tag could not be created successfully',
            ], 500);
        }
    }

    public function attachTag(Request $request, Product $product, Tag $tag)
    {
        try {
            $this->tagService->attachTag($product, $tag);

            return response()->json([
                'message' => 'Tag ' . $tag->name . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error adding Tag: ' . $th->getMessage());

            return response()->json([
                'message' => 'Tag could not be added successfully',
            ], 500);
        }
    }

    public function update(UpdateTagRequest $request, Tag $tag)
    {
        try {
            $this->tagService->updateTag($tag, $request->all());

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
            $this->tagService->destroyTag($tag);

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

    public function detachTag(Product $product, Tag $tag)
    {
        try {
            $this->tagService->detachTag($product, $tag);

            return response()->json([
                'message' => 'Tag ' . $tag->name . ' removed from ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error removing Tag: ' . $th->getMessage());

            return response()->json([
                'message' => 'Tag could not be removed successfully',
            ], 500);
        }
    }
}
