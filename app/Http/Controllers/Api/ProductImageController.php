<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Http\Requests\StoreProductImageRequest;

class ProductImageController extends Controller
{
    public function store(StoreProductImageRequest $request, Product $product)
    {
        $image = $product->images()->create(
            $request->validated()
        );

        return response()->json([
            'data' => $image
        ], 201);
    }

    public function destroy(Product $product, ProductImage $image)
    {
        $image->delete();

        return response()->json([
            'message' => 'Product image deleted successfully'
        ]);
    }
}
