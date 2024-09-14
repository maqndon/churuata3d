<?php

namespace App\Http\Controllers\Api;

use App\Models\Bom;
use App\Models\Product;
use App\Http\Resources\BomResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bom\StoreBomRequest;

class BomController extends Controller
{
    public function show(Product $product)
    {
        try {
            $bill_of_materials = BomResource::collection($product->bill_of_materials);

            return response()->json([
                'boms' => $bill_of_materials
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'An error occurred'
            ], 404);
        }
    }

    public function store(StoreBomRequest $request, Product $product)
    {
        try {
            $bom = new Bom();
            $bom->bomable_type = Product::class;
            $bom->bomable_id = $product->id;
            $bom->qty = $request->input('qty');
            $bom->item = $request->input('item');
            $bom->save();

            return response()->json([
                'message' => 'Material ' . $request->item . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error adding material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be created successfully',
            ], 500);
        }
    }

    public function update(Product $product, Bom $bom)
    {
        try {
            $product->bill_of_materials()->update([$bom->id]);
            return response()->json([
                'message' => 'Bom ' . $bom->name . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error adding Bill of Material: ' . $th->getMessage());
            return response()->json([
                'message' => 'Material could not be added successfully',
            ], 500);
        }
    }

    public function destroy(Product $product, Bom $bom)
    {
        try {
            $product->bill_of_materials()->find($bom->id)->delete();
            return response()->json([
                'message' => 'Material ' . $bom->item . ' removed from ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error removing Material: ' . $th->getMessage());
            return response()->json([
                'message' => 'Material ' . $bom->item . ' could not be removed successfully',
            ], 500);
        }
    }
}
