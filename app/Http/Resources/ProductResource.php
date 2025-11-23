<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => $this->title,
            'short_description' => $this->description,
            'description' => $this->description,
            'is_active' => (bool) $this->is_active,
            'is_featured' => (bool) $this->is_featured,

            // cover image for shop card
            'cover_image' => $this->cover_image_url,

            'images' => $this->whenLoaded(
                'images',
                fn() =>
                $this->images->map(fn($img) => [
                    'id' => $img->id,
                    'url' => $img->path,
                    'sort_order' => (int) $img->sort_order,
                    'is_cover' => (bool) $img->is_cover,
                    'alt_text' => $img->alt_text,
                ])->values()
            ),

            // variants matrix (color+size+stock+price)
            'variants' => ProductVariantResource::collection(
                $this->whenLoaded('variants')
            ),

            // convenience fields for UI filters
            'available_colors' => $this->whenLoaded('variants', function () {
                return $this->variants
                    ->loadMissing('color')
                    ->pluck('color')
                    ->unique('id')
                    ->values()
                    ->map(fn($c) => [
                        'id' => $c->id,
                        'name' => $c->name,
                        'hex' => $c->hex,
                    ]);
            }),

            'available_sizes' => $this->whenLoaded('variants', function () {
                return $this->variants
                    ->loadMissing('size')
                    ->pluck('size')
                    ->unique('id')
                    ->values()
                    ->map(fn($s) => [
                        'id' => $s->id,
                        'name' => $s->name,
                    ]);
            }),

            // price range for shop listing (min-max)
            'price_range' => $this->whenLoaded('variants', function () use ($request) {
                $currency = $request->get('currency_model');
                $rate = $currency?->rate_to_base ?? 1;

                $prices = $this->variants
                    ->map(fn($variant) => $variant->price_override ?? $this->base_price)
                    ->map(fn($p) => round($p * $rate, 2));

                return [
                    'min' => (float) $prices->min(),
                    'max' => (float) $prices->max(),
                ];
            }),
        ];
    }
}
