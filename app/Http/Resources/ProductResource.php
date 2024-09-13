<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    const NOT_APPLICABLE = 'Don\'t apply';

    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'created by' =>  $this->created_by,
            'licence' => $this->licence->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'stock' => $this->stock !== null ? $this->stock : $this->NOT_APPLICABLE,
            'price' => $this->price,
            'sale price' => $this->sale_price,
            'status' => $this->status,
            'is featured' => $this->is_featured,
            'is downloadable' => $this->is_downloadable,
            'is free' => $this->is_free,
            'is printable' => $this->is_printable,
            'is parametric' => $this->is_parametric,
            'related parametric' => $this->related_parametric,
            'downloads' => $this->downloads,
            'created at' => $this->created_at,
            'updated at' => $this->updated_at,
        ];
    }
}
