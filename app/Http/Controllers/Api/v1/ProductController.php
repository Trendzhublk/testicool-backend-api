<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Currency;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'currency' => 'nullable|string|exists:currencies,code',
            'search'   => 'nullable|string|max:120',
            'featured' => 'nullable|boolean',
            'per_page' => 'nullable|integer|min:1|max:50',
        ]);

        $currency = Currency::where('code', $request->currency ?? 'USD')->first();
        $request->merge(['currency_model' => $currency]);

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

        return ProductResource::collection($products);
    }

    public function show(Request $request, Product $product)
    {
        if (!$product->is_active) {
            return response()->json(['message' => 'Product not available'], 404);
        }

        $currency = Currency::where('code', $request->currency ?? 'USD')->first();
        $request->merge(['currency_model' => $currency]);

        $product->load([
            'images:id,product_id,variant_id,path,sort_order,alt_text',
            'variants:id,product_id,sku,price_override,stock_qty,color_id,size_id,weight,is_active',
            'variants.color:id,name,hex',
            'variants.size:id,name',
            'variants.size.charts:id,size_id,unit,min_value,max_value', // for PDP size guide
        ]);

        return new ProductResource($product);
    }
}
