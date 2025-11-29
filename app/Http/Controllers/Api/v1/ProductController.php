<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\CurrencyConversionService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function __construct(private CurrencyConversionService $currencyService) {}

    public function index(Request $request)
    {
        $supported = $this->currencyService->supportedCodes();

        $request->validate([
            'currency' => ['nullable', 'string', Rule::in($supported)],
            'search'   => 'nullable|string|max:120',
            'featured' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $currencyCode = $this->currencyService->normalize($request->currency);
        $currencyRate = $this->currencyService->rate($currencyCode);
        $currencySymbol = $this->currencyService->symbol($currencyCode);
        $request->merge([
            'currency_code' => $currencyCode,
            'currency_rate' => $currencyRate,
            'currency_symbol' => $currencySymbol,
        ]);
        $currencyPayload = [
            'code' => $currencyCode,
            'symbol' => $currencySymbol,
            'rate_to_base' => $currencyRate,
        ];

        $q = Product::query()
            ->where('is_active', true)
            ->with([
                'images:id,product_id,variant_id,path,sort_order,alt_text',
                'variants:id,product_id,sku,price_override,stock_qty,color_id,size_id,weight,is_active',
                'variants.color:id,name,hex',
                'variants.size:id,name',
            ]);

        if ($request->filled('search')) {
            $s = $request->search;
            $q->where(function ($w) use ($s) {
                $w->where('title', 'like', "%$s%")
                    ->orWhere('description', 'like', "%$s%");
            });
        }

        if ($request->boolean('featured')) {
            $q->where('is_featured', true);
        }

        $perPage = $request->per_page ?? 12;
        $products = $q->orderBy('created_at', 'desc')->paginate($perPage);

        return ProductResource::collection($products)
            ->additional([
                'currency' => $currencyPayload,
                'currencies' => $this->currencyService->currenciesWithRates(),
            ]);
    }

    public function show(Request $request, Product $product)
    {
        if (!$product->is_active) {
            return response()->json(['message' => 'Product not available'], 404);
        }

        $currencyCode = $this->currencyService->normalize($request->currency);
        $currencyRate = $this->currencyService->rate($currencyCode);
        $currencySymbol = $this->currencyService->symbol($currencyCode);
        $request->merge([
            'currency_code' => $currencyCode,
            'currency_rate' => $currencyRate,
            'currency_symbol' => $currencySymbol,
        ]);
        $currencyPayload = [
            'code' => $currencyCode,
            'symbol' => $currencySymbol,
            'rate_to_base' => $currencyRate,
        ];

        $product->load([
            'images:id,product_id,variant_id,path,sort_order,alt_text',
            'variants:id,product_id,sku,price_override,stock_qty,color_id,size_id,weight,is_active',
            'variants.color:id,name,hex',
            'variants.size:id,name',
            'variants.size.charts:id,size_id,unit,min_value,max_value', // for PDP size guide
        ]);

        return (new ProductResource($product))
            ->additional([
                'currency' => $currencyPayload,
                'currencies' => $this->currencyService->currenciesWithRates(),
            ]);
    }
}
