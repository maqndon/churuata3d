<?php

namespace App\Http\Controllers\Api;

use App\Models\Bom;
use App\Models\Product;
use App\Http\Resources\BomResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Bom\StoreBomRequest;
use App\Http\Requests\Bom\UpdateBomRequest;
use App\Services\BillOfMaterialService;

class BomController extends Controller
{
    private $bomService;

    public function __construct(BillOfMaterialService $bomService)
    {
        $this->bomService = $bomService;
    }

    public function show(Product $product)
    {
        try {
            $bill_of_materials = BomResource::collection($product->bill_of_materials);

            return response()->json([
                'boms' => $bill_of_materials
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('There is no material stored with this ID: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be found',
            ], 500);
        }
    }

    public function store(StoreBomRequest $request, Product $product)
    {
        try {
            $this->bomService->createBom($request->all(), $product);

            return response()->json([
                'message' => 'Material ' . $request->item . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be created successfully',
            ], 500);
        }
    }

    public function update(UpdateBomRequest $request, Product $product, Bom $bom)
    {
        try {
            $this->bomService->updateBom($request->all(), $bom);

            return response()->json([
                'message' => 'Material ' . $bom->item . ' added to ' . $product->title . ' successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error updating material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be updated successfully',
            ], 500);
        }
    }

    public function destroy(Product $product, Bom $bom)
    {
        try {
            $this->bomService->deleteBom($bom);

            return response()->json([
                'message' => 'Material ' . $bom->item . ' deleted from ' . $product->title . ' successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error deleting material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be deleted successfully',
            ], 500);
        }
    }
}
