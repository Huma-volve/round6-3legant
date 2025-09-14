<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory;
    protected $fillable = ['author_id', 'title', 'slug', 'excerpt', 'cover_image', 'body', 'attachments', 'status', 'published_at'];
    protected $casts = [
        'published_at' => 'datetime',
        'attachments' => 'array',
    ];

    // auto-generate slug if missing
    public static function booted()
    {
        static::saving(function ($article) {
            if (empty($article->slug) && !empty($article->title)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    public function author(){
        return $this->belongsTo(User::class, 'author_id');
    }

}
