<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PrintSettingsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'description' => $this->description,
            'print_strength' => $this->print_strength,
            'resolution' => $this->resolution,
            'infill' => (string) $this->infill,
            'top_layers' => (string) $this->top_layers,
            'bottom_layers' => (string) $this->bottom_layers,
            'walls' => (string) $this->walls,
            'speed' => (string) $this->speed,
        ];
    }
}
