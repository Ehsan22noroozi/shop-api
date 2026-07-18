<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with([
            'category',
            'optionValues.option'
        ])->get();

        return ProductResource::collection($products);
    }

    public function show(Product $product)
    {
        $product->load([
            'category',
            'optionValues.option'
        ]);

        return new ProductResource($product);
    }
}
