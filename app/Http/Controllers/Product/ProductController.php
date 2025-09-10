<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->get();
        return response()->json($products, 200);
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        return response()->json($product, 200);
    }

    public function store(ProductRequest $request)
    {
        $product = Product::create($request->validated());
        return response()->json($product, 201);
    }

    public function update(ProductRequest $request, $productID)
    {
        $product = Product::findOrFail($productID);
        $product->update($request->validated());
        return response()->json($product, 200);
    }

    public function destroy($productID)
    {
        $product = Product::findOrFail($productID);
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 200);
    }

    public function newProducts(){
        $products = Product::orderBy('created_at', 'desc')->take(10)->get();
        return response()->json([
            'status' => true,
            'message' => 'New products retrieved successfully',
            'data' => $products
        ], 200);
    }


}
