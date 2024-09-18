<?php

namespace App\Services;

use App\Models\PrintingMaterial;

class PrintingMaterialService
{
    public function storeMaterial(array $data)
    {
        $material = new PrintingMaterial();
        $material->name = $data['name'];
        $material->nozzle_size = $data['nozzle_size'];
        $material->min_hot_bed_temp = $data['min_hot_bed_temp'];
        $material->max_hot_bed_temp = $data['max_hot_bed_temp'];
        $material->save();

        return $material;
    }

    public function updateMaterial(PrintingMaterial $material, array $data)
    {
        // Filter the data that has changed
        $data = array_filter($data, function ($value) {
            return !is_null($value);
        });

        // Update the material with the filtered data
        $material->update($data);

        return $material;
    }

    public function destroyMaterial(PrintingMaterial $material)
    {
        $material->delete();

        return true;
    }
}