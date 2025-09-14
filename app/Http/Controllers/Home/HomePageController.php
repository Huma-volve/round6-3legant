<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Models\Category;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    use ApiResponseTrait;
    public function homeCategories()
    {
        $categories = Category::select('id', 'name', 'image')->get();
        return $this->successResponse($categories, 'Categories retrieved successfully');
    }
    public function newProducts()
    {
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        return $this->successResponse($products, 'New products retrieved successfully');
    }

    public function mostViewedProducts()
    {
        $products = Product::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();
        return $this->successResponse($products, 'Most viewed products retrieved successfully');
    }

    public function featuredCollections(){
        $collections = Category::where('parent_id', null)
            ->where('is_featured', true)
            ->with('children:id,name,parent_id,image')
            ->get(['id', 'name', 'image']);

            return $this->successResponse($collections, 'Featured collections retrieved successfully');
    }

    public function bestSellerProducts(){
        $products = Product::joinSub(function ($query) {
                $query->from('order_items')
                    ->join('orders', 'order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', 'completed')
                    ->select(
                        'order_items.product_id',
                        DB::raw('SUM(order_items.quantity) as total_sold'),
                        DB::raw('COUNT(DISTINCT order_items.order_id) as total_orders')
                    )
                    ->groupBy('order_items.product_id');
            }, 'sales', function ($join) {
                $join->on('products.id', '=', 'sales.product_id');
            })
            ->select('products.*', 'sales.total_sold', 'sales.total_orders')
            ->orderByDesc('sales.total_sold')
            ->paginate(10);

            return $this->successResponse($products, 'Best seller products retrieved successfully');
    }
}
