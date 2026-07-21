<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CartResource;
use App\Http\Resources\CartItemResource;
use App\Http\Requests\StoreCartItemRequest;
use App\Models\CartItem;
use App\Models\Product;

class CartController extends Controller
{
    public function index()
    {
        $cart = request()->cart;

        $cart->load('items.product');

        return response()->json([
            'data' => new CartResource($cart),
        ], 200);
    }

    public function storeItem(StoreCartItemRequest $request)
    {
        $cart = $request->cart;

        $product = Product::findOrFail(
            $request->product_id
        );

        $cartItem = CartItem::where([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
        ])->first();

        if ($cartItem) {
            $cartItem->increment(
                'quantity',
                $request->quantity
            );

            $cartItem->refresh();
        } else {
            $cartItem = CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'price' => $product->price,
            ]);
        }

        return new CartItemResource($cartItem);
    }
}
