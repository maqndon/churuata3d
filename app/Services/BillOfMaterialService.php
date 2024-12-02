<?php

namespace App\Services;

use App\Models\Bom;
use App\Models\Product;
use App\Http\Resources\BomResource;

class BillOfMaterialService
{
    public static function getBillOfMaterials($type, $id)
    {
        $bom = Bom::where('bomable_id', $id)
            ->where('bomable_type', $type)
            ->get();
            
            return BomResource::collection($bom);
    }

    public function createBom(array $data, Product $product)
    {
        $existingBom = Bom::where([
            'bomable_type' => Product::class,
            'bomable_id' => $product->id,
            'item' => $data['item'],
        ])->first();

        if ($existingBom) {
            throw new \Exception('Material ' . $data['item'] . ' already exists');
        }

        $bom = new Bom();
        $bom->bomable_type = Product::class;
        $bom->bomable_id = $product->id;
        $bom->qty = $data['qty'];
        $bom->item = $data['item'];
        $bom->save();

        return $bom;
    }

    public function updateBom(array $data, Bom $bom)
    {
        $bom->update($data);
        return $bom;
    }


    public function deleteBom(Bom $bom)
    {
        $bom->delete();
        return true;
    }
}
