<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'subtitle' => $this->subtitle,
            'slug' => $this->slug,
            'description' => $this->description,
            'price' => $this->price,
            'stock' => $this->stock,

            'category' => $this->whenLoaded('category', function () {
                return [
                    'id' => $this->category->id,
                    'name' => $this->category->name,
                ];
            }),

            'options' => $this->whenLoaded('optionValues', function () {
                return $this->optionValues->map(function ($optionValue) {
                    return [
                        'option' => $optionValue->option->name,
                        'value' => $optionValue->value,
                    ];
                });
            }),

            'images' => $this->whenLoaded('images', function () {
                return $this->images->map(function ($image) {
                    return [
                        'id' => $image->id,
                        'path' => $image->path,
                        'alt' => $image->alt,
                        'is_primary' => (bool) $image->is_primary,
                        'sort_order' => $image->sort_order,
                    ];
                });
            }),
        ];
    }
}
