<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductVariantResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $currency = $request->get('currency_model'); // injected in controller
        $rate = $currency?->rate_to_base ?? 1;
        $symbol = $currency?->symbol ?? '$';
        $code = $currency?->code ?? 'USD';

        $basePrice = $this->price_override ?? $this->product?->base_price ?? 0;
        $converted = round($basePrice * $rate, 2);

        return [
            'id' => $this->id,
            'sku' => $this->sku,
            'price_base' => (float) $basePrice,
            'price' => $converted,
            'currency' => [
                'code' => $code,
                'symbol' => $symbol,
                'rate_to_base' => (float) $rate,
            ],
            'stock' => (int) $this->stock_qty,
            'is_in_stock' => $this->stock_qty > 0,

            'color' => $this->whenLoaded('color', fn() => [
                'id' => $this->color->id,
                'name' => $this->color->name,
                'hex' => $this->color->hex,
            ]),

            'size' => $this->whenLoaded('size', fn() => [
                'id' => $this->size->id,
                'name' => $this->size->name,
            ]),
        ];
    }
}
