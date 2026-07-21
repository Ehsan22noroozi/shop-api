<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Str;
use App\Models\Cart;

class CartSessionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $sessionId = $request->header('X-Cart-Session');

        if (!$sessionId) {
            $sessionId = Str::uuid()->toString();
        }

        $cart = Cart::firstOrCreate(
            [
                'session_id' => $sessionId,
            ],
            [
                'status' => 'active',
            ]
        );

        $request->merge([
            'cart_session_id' => $sessionId,
            'cart' => $cart,
        ]);

        $response = $next($request);

        $response->headers->set(
            'X-Cart-Session',
            $sessionId
        );

        return $response;
    }
}
