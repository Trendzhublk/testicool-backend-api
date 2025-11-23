<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CurrencyResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'code'         => $this->code,
            'symbol'       => $this->symbol,
            'rate_to_base' => (float) $this->rate_to_base,
            'is_default'   => (bool) $this->is_default,
        ];
    }
}
