<?php

namespace App\Traits;

use App\Models\Bom;

trait BillOfMaterials
{
    public static function getBillOfMaterials($type, $id)
    {
        $bom = Bom::where('bomable_id', $id)
            ->where('bomable_type', $type)
            ->get();
        return $bom;
    }
}
