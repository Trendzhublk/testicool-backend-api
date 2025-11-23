<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CurrencyResource;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CurrencyController extends Controller
{
    /**
     * Public list of currencies for FE dropdown + pricing.
     * Cached for performance.
     */
    public function index(Request $request)
    {
        $ttl = now()->addHours(6); // good balance for currency rates

        $currencies = Cache::remember('currencies.public', $ttl, function () {
            return Currency::query()
                // if you don't have is_active column, remove this line
                ->when(schema_has_column('currencies', 'is_active'), fn($q) => $q->where('is_active', true))
                ->orderByDesc('is_default')
                ->orderBy('code')
                ->get();
        });

        return CurrencyResource::collection($currencies);
    }
}

/**
 * Small helper to avoid crashes if is_active isn't there.
 * Put this in the same file for now, later move to a helper class.
 */
function schema_has_column(string $table, string $column): bool
{
    return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
}
