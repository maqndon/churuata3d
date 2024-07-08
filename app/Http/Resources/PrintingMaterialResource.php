<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintingMaterialResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
            'nozzle_size' => $this->nozzle_size,
            'min_hot_bed_temp' => (string) $this->min_hot_bed_temp,
            'max_hot_bed_temp' => (string) $this->max_hot_bed_temp,
        ];
    }
}
