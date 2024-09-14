<?php

namespace App\Http\Controllers\Api;

use App\Models\Bom;
use App\Models\Product;
use App\Http\Resources\BomResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bom\StoreBomRequest;
use App\Http\Requests\Bom\UpdateBomRequest;

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
            $existingBom = Bom::where([
                'bomable_type' => Product::class,
                'bomable_id' => $product->id,
                'item' => $request->input('item'),
            ])->first();

            if ($existingBom) {
                return response()->json([
                    'message' => 'Material ' . $request->item . ' already exists',
                ], 409);
            }

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

    public function update(UpdateBomRequest $request, Product $product, Bom $bom)
    {
        try {
            // Filter the data that has changed
            $data = array_filter($request->only([
                'qty',
                'item',
            ]), function ($value) {
                return !is_null($value);
            });

            // Update the category with the filtered data
            $bom->update($data);

            return response()->json([
                'message' => 'Material ' . $bom->item . ' added to ' . $product->title . ' successfully',
            ], 201);
        } catch (\Throwable $th) {
            // Log error and return a JSON response
            \Log::error('Error adding material: ' . $th->getMessage());
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
            \Log::error('Error removing material: ' . $th->getMessage());
            return response()->json([
                'message' => 'Material ' . $bom->item . ' could not be removed successfully',
            ], 500);
        }
    }
}
