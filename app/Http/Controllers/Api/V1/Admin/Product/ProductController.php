<?php

namespace App\Http\Controllers\Api\V1\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('subcategory')->get();
        return response()->json($products, 200);
    }

    public function show($id)
    {

        $product = Product::with('subcategory')->findOrFail($id);
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

}
