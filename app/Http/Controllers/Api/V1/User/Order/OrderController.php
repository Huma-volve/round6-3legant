<?php

namespace App\Http\Controllers\Api\V1\User\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Order\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
   
    /**
     * List user orders.
     */
    public function index()
    {
        $orders = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        return response()->json($orders);
    }

    /**
     * Place an order from cart.
     */
    public function store(OrderRequest $request)
    {
        $userId = Auth::id();

        $cart = Cart::with('items.product')->where('user_id', $userId)->first();

        if (!$cart || $cart->items->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 400);
        }

        return DB::transaction(function () use ($cart, $request, $userId) {
            // Calculate total price
            $total = $cart->items->sum(fn($item) => $item->quantity * $item->product->price);

            // Apply coupon if present
            if ($request->coupon_id) {
                // you may implement coupon discount logic here
            }

            // Create the order
            $order = Order::create([
                'user_id'        => $userId,
                'cart_id'        => $cart->id,
                'total_price'    => $total,
                'status'         => 'pending',
                'payment_status' => $request->payment_status ?? 'unpaid',
                'address_id'     => $request->address_id,
                'coupon_id'      => $request->coupon_id,
            ]);

            // Copy cart items into order items
            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity'   => $cartItem->quantity,
                    'price'      => $cartItem->product->price,
                ]);
            }

            // Optional: clear cart
            $cart->items()->delete();

            return response()->json([
                'message' => 'Order placed successfully',
                'order'   => $order->load('orderItems.product'),
            ], 201);
        });
    }

    /**
     * Show single order.
     */
    public function show($id)
    {
        $order = Order::with('orderItems.product')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        return response()->json($order);
    }

    /**
     * Cancel an order (if still pending).
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())->findOrFail($id);

        if ($order->status !== 'pending') {
            return response()->json(['message' => 'Order cannot be cancelled'], 400);
        }

        $order->status = 'cancelled';
        $order->save();

        return response()->json(['message' => 'Order cancelled']);
    }


}
