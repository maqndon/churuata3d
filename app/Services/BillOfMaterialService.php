<?php

namespace App\Services;

use App\Models\Bom;

class BillOfMaterialService
{
    public static function getBillOfMaterials($type, $id)
    {
        $bom = Bom::where('bomable_id', $id)
            ->where('bomable_type', $type)
            ->get();
        return $bom;
    }
}
