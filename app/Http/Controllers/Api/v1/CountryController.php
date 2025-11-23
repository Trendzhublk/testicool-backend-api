<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index(Request $request)
    {
        $shippingOnly = $request->boolean('shipping_only');

        $shippingCountryCodes = collect();
        if ($shippingOnly) {
            $shippingCountryCodes = ShippingRate::query()
                ->where('is_active', true)
                ->whereNotNull('country_code')
                ->pluck('country_code')
                ->filter()
                ->map(fn ($code) => strtoupper($code))
                ->unique()
                ->values();
        }

        $countries = Country::query()
            ->when($shippingOnly && $shippingCountryCodes->isNotEmpty(), fn($q) => $q->whereIn('code', $shippingCountryCodes))
            ->orderBy('name')
            ->get(['code', 'name']);

        return response()->json([
            'data' => $countries,
            'shipping_only' => $shippingOnly,
        ]);
    }
}
