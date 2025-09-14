<?php

namespace App\Http\Controllers\Api\V1\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
  public function index()
    {
        $categories = Category::with('children', 'parent')->get();
        return response()->json($categories, 200);
    }

    public function show($catID)
    {    
        $category = Category::with('children', 'parent')->findOrFail($catID);
        return response()->json($category, 200);
    }
    public function store(CategoryRequest $request)
    {
        $data = $request->validated();
        $category = Category::create($data);
        return response()->json($category);
        
    }
     public function update(CategoryRequest $request, $catID)
    {

        $category = Category::findOrFail($catID);
        $data = $request->validated();
        $category->update($data);
        return response()->json($category); 
    }
    
     public function destroy($catID)
     {
        $category = Category::findOrFail($catID);
        $category->delete();
        return response()->json(['message' => 'Category deleted successfully']);
    }
} 
