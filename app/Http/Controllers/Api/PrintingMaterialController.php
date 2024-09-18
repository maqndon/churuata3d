<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\PrintingMaterial;
use App\Http\Controllers\Controller;
use App\Services\PrintingMaterialService;
use App\Http\Resources\PrintingMaterialResource;
use App\Http\Requests\PrintingMaterials\StorePrintingMaterialRequest;
use App\Http\Requests\PrintingMaterials\UpdatePrintingMaterialRequest;

class PrintingMaterialController extends Controller
{
    private $printingMaterialService;

    public function __construct(PrintingMaterialService $printingMaterialService)
    {
        $this->printingMaterialService = $printingMaterialService;
    }
    
    public function index(Request $request)
    {
        try {
            $perPage = $request->input('per_page', 5);
            $tags = new PrintingMaterialResource(PrintingMaterial::paginate($perPage));

            return PrintingMaterialResource::collection($tags);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'No Printing Materials found'
            ], 404);
        }
    }

    public function show(PrintingMaterial $printingMaterial)
    {
        try {
            return new PrintingMaterialResource($printingMaterial);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException) {

            return response()->json([
                'message' => 'Material not found'
            ], 404);
        }
    }

    public function store(StorePrintingMaterialRequest $request)
    {
        try {
            $this->printingMaterialService->storeMaterial($request->all());

            return response()->json([
                'message' => 'Material ' . $request->input('name') . ' created successfully',
            ], 201);
        } catch (\Throwable $th) {
            \Log::error('Error creating Material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be created successfully',
            ], 500);
        }
    }

    public function update(UpdatePrintingMaterialRequest $request, PrintingMaterial $printingMaterial)
    {
        try {
            $this->printingMaterialService->updateMaterial($printingMaterial, $request->all());

            return response()->json([
                'message' => 'Material ' . $printingMaterial->name . ' updated successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error updating material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be updated successfully',
            ], 500);
        }
    }

    public function destroy(PrintingMaterial $printingMaterial)
    {
        try {
            $this->printingMaterialService->destroyMaterial($printingMaterial);

            return response()->json([
                'message' => 'Material ' . $printingMaterial->name . ' deleted successfully',
            ], 200);
        } catch (\Throwable $th) {
            \Log::error('Error deleting material: ' . $th->getMessage());

            return response()->json([
                'message' => 'Material could not be deleted successfully',
            ], 500);
        }
    }
}
