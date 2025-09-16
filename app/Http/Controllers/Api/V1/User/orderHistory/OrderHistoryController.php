<?php

namespace App\Http\Controllers\Api\V1\User\orderHistory;

use App\Http\Controllers\Controller;
use App\Http\Resources\OrderHistoryResource;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index (Request $request)
    {
        $user = $request->user();

        $perPage = (int) $request->query('per_page', 10);
        $perPage = max(1, min($perPage, 100));

        if (!$user->orders()->exists()) {
            return response()->json([
                'status'  => true,
                'message' => 'No orders found',
            ], 200);
        }

        $orders = $user->orders()
        ->orderByDesc('created_at')
        ->paginate($perPage);

        return OrderHistoryResource::collection($orders);
    }
}
