<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Support\Str;
use App\Http\Requests\UpdateProductRequest;

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

    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['title']);

        $product = Product::create($data);

        if ($request->has('option_values')) {
            $product->optionValues()->sync($request->option_values);
        }

        $product->load([
            'category',
            'optionValues.option'
        ]);

        return new ProductResource($product);
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $product->update($data);

        if (isset($data['option_values'])) {
            $product->optionValues()->sync($data['option_values']);
        }

        $product->load([
            'category',
            'optionValues.option'
        ]);

        return new ProductResource($product);
    }
}
