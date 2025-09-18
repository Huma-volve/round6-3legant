<?php

namespace App\Http\Controllers\Api\V1\User\Cart;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::with('items.product')
            ->where('user_id', Auth::id())
            ->first();

        if (!$cart) {
            return response()->json(['message' => 'Cart is empty'], 200);
        }

        $total = $cart->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });

        return response()->json([
            'cart' => $cart,
            'total_price' => $total,
        ]);
    }

    /**
     * Add product to cart (or update if exists).
     */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $product = Product::findOrFail($request->product_id);

        if ($product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock'], 400);
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);

        $item = $cart->items()->where('product_id', $product->id)->first();

        if ($item) {
            // update quantity
            $item->quantity += $request->quantity;
            $item->save();
        } else {
            // create new item
            $cart->items()->create([
                'product_id' => $product->id,
                'quantity'   => $request->quantity,
            ]);
        }

        return response()->json([
            'message' => 'Product added to cart',
            'cart' => $cart->load('items.product')
]);

    }

    /**
     * Update quantity of an item.
     */
    public function update(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $item = CartItem::whereHas('cart', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($itemId);

        if ($item->product->stock < $request->quantity) {
            return response()->json(['message' => 'Not enough stock'], 400);
        }

        $item->quantity = $request->quantity;
        $item->save();

        return response()->json(['message' => 'Cart item updated']);
    }
    public function increment($itemId)
{
    $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
        ->findOrFail($itemId);

    if ($item->product->stock < $item->quantity + 1) {
        return response()->json(['message' => 'Not enough stock'], 400);
    }

    $item->quantity++;
    $item->save();

    return response()->json(['message' => 'Quantity increased']);
}

public function decrement($itemId)
{
    $item = CartItem::whereHas('cart', fn($q) => $q->where('user_id', Auth::id()))
        ->findOrFail($itemId);

    if ($item->quantity <= 1) {
        return response()->json(['message' => 'Quantity cannot be less than 1'], 400);
    }

    $item->quantity--;
    $item->save();

    return response()->json(['message' => 'Quantity decreased']);
}


    /**
     * Remove a product from cart.
     */
    public function destroy($itemId)
    {
        $item = CartItem::whereHas('cart', function ($q) {
            $q->where('user_id', Auth::id());
        })->findOrFail($itemId);

        $item->delete();

        return response()->json(['message' => 'Product removed from cart']);
    }

    /**
     * Clear the user's cart.
     */
    public function clear()
    {
        $cart = Cart::where('user_id', Auth::id())->first();

        if ($cart) {
            $cart->items()->delete();
        }

        return response()->json(['message' => 'Cart cleared']);
    }
}
