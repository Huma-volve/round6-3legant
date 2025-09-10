<?php

namespace App\Http\Controllers\Home;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomePageController extends Controller
{
    public function homeCategories()
    {
        $categories = Category::select('id', 'name', 'image')->get();
        return response()->json([
            'status' => true,
            'message' => 'Categories retrieved successfully',
            'data' => $categories
        ], 200);
    }
    public function newProducts()
    {
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json([
            'status' => true,
            'message' => 'New products retrieved successfully',
            'data' => $products
        ], 200);
    }

    public function mostViewedProducts()
    {
        $products = Product::withAvg('reviews', 'rating')
            ->orderByDesc('reviews_avg_rating')
            ->take(10)
            ->get();
        return response()->json([
            'status' => true,
            'message' => 'Most viewed products retrieved successfully',
            'data' => $products
        ], 200);
    }
}
