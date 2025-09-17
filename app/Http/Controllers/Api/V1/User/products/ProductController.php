<?php

namespace App\Http\Controllers\Api\V1\User\products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Traits\ApiResponseTrait;
class ProductController extends Controller
{
    use ApiResponseTrait;

    public function index(Request $request)
    {

        $query = Product::with('subcategory');

        // filter by subcategory
        if ($request->has('subcategory_id')) {
            $query->where('subcategory_id', $request->subcategory_id);
        }

        // filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // sorting
        if ($request->has('sort_by')) {
            switch ($request->sort_by) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'latest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }


        $products = $query->paginate(12);


        if ($products->isEmpty()) {
            return $this->errorResponse('No products found.', 404);
        }


        return $this->successResponse($products, 'Product list');
    }
    public function searchProducts(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:3',
        ]);

        $searchTerm = $request->input('search');

        $products = Product::where('name', 'LIKE', "%{$searchTerm}%")
            ->orWhere('description', 'LIKE', "%{$searchTerm}%")
            ->paginate(12);

            
        if ($products->isEmpty()) {
            return $this->errorResponse('No products found matching your search criteria.');
        }

        return $this->successResponse($products, 'Search results');
    }
}
