<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function __construct(private CurrencyConversionService $currencyService) {}

    /**
     * Public list of currencies for FE dropdown + pricing.
     * Rates come from a free provider (exchangerate.host) and are cached.
     */
    public function index(Request $request)
    {
        return response()->json($this->currencyService->currenciesWithRates());
    }
}
