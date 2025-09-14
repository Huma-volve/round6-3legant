<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Traits\ApiResponseTrait;

class ArticleController extends Controller
{
    use ApiResponseTrait;
    public function index(){
        $articles = Article::with('author:id,username')
            ->select('id', 'title', 'slug', 'cover_image', 'published_at')
            ->orderBy('published_at', 'desc')
            ->paginate(10);

        return $this->successResponse($articles, 'Articles retrieved successfully');
    }

    public function show($slug)
    {
        $article = Article::with('author:id,username')
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->successResponse($article, 'Article retrieved successfully');
    }
}
