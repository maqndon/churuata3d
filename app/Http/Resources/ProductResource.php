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
            'title' => $this->title,
            'slug' => $this->slug,
            'sku' => $this->sku,
            'excerpt' => $this->excerpt,
            'body' => $this->body,
            'stock' => $this->stock !== null ? $this->stock : $this->NOT_APPLICABLE,
            'price' => $this->price,
            'sale_price' => $this->sale_price,
            'status' => $this->status,
            'licence' => LicenceResource::make($this->whenLoaded('licence')),
            'created by' =>  UserResource::make($this->whenLoaded('user')),
            'bill of materials' =>  BomResource::collection($this->whenLoaded('bill_of_materials')),
            'tags' =>  TagResource::collection($this->whenLoaded('tags')),
            'categories' =>  CategoryResource::collection($this->whenLoaded('categories')),
            'comments' =>  CommentResource::collection($this->whenLoaded('comments')),
            'seo' =>  SeoResource::make($this->whenLoaded('seos')),
            'files' =>  FileResource::make($this->whenLoaded('files')),
            'images' =>  ImageResource::make($this->whenLoaded('images')),
            'printing materials' =>  PrintingMaterialResource::collection($this->whenLoaded('printing_materials')),
            'print settings' =>  PrintSettingsResource::collection($this->whenLoaded('print_settings')),
            'print supports/rafts' =>  PrintSupportRaftResource::make($this->whenLoaded('print_supports_rafts')),
            'sales' =>  SaleResource::collection($this->whenLoaded('sales')),
            'physical attributes' =>  ProductPhysicalAttributeResource::make($this->whenLoaded('product_physical_attributes')),
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
