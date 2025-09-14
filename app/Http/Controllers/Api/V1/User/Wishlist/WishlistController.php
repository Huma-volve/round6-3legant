<?php

namespace App\Http\Controllers\Api\V1\User\Wishlist;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    // to show all products in wishlist
    public function index (Request $request)
    {
        $user = $request->user();

        $wishlistProducts = $user->wishlist()->with('product.subcategory', 'product.reviews')->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Wishlist products retrieved successfully',
            'data' => $wishlistProducts
        ]);
    }

    // to add a product to the wishlist
    public function addProductToWishlist (Request $request, $productId)
    {
        $user = $request->user();

        $product = Product::find($productId);
        if(! $product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $exists = Wishlist::where('user_id', $user->id)
        ->where('product_id', $productId)->first();

        if ($exists){
            return response()->json([
                'success' => false,
                'message' => 'Product already in wishlist',
            ], 409);
        }

        Wishlist::create([
            'user_id' => $user->id,
            'product_id' => $productId,
        ]);

        return response()->json([
            'success' => true,
            'message' => "Product added to wishlist",
        ], 201);
    }

    public function removeProductFromWishlist (Request $request, $productId)
    {
        $user = $request->user();

        $wishlistItem = Wishlist::where('user_id', $user->id)
        ->where('product_id', $productId)
        ->first();

        if (!$wishlistItem){
            return response()->json([
                'success' => false,
                'message' => 'Product not in your wishlist',
            ], 404);
        }

        $wishlistItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product removed from your wishlist',
        ]);
    }
}
