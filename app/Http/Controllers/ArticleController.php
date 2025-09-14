<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function index(){
        $articles = Article::with('author:id,username')
            ->select('id', 'title', 'slug', 'cover_image', 'published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'message' => 'Articles retrieved successfully',
            'data' => $articles
        ], 200);
    }

    public function show($slug)
    {
        $article = Article::with('author:id,username')
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Articles retrieved successfully',
            'data' => $article
        ], 200);
    }
}
