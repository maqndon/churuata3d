<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductPhysicalAttributeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'weight' => $this->weight,
            'length' => $this->length,
            'width' => $this->width,
            'height' => $this->height,
        ];

    }
}
