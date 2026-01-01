<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    public function index(): JsonResponse
    {
        $posts = BlogPost::query()
            ->published()
            ->orderByDesc('published_at')
            ->get();

        return response()->json([
            'data' => $posts->map(fn (BlogPost $post) => $this->formatPost($post)),
        ]);
    }

    public function show(string $slug): JsonResponse
    {
        $post = BlogPost::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return response()->json([
            'data' => $this->formatPost($post),
        ]);
    }

    private function formatPost(BlogPost $post): array
    {
        return [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'summary' => $post->summary,
            'content' => $post->content,
            'cover_image_url' => $post->cover_image_path
                ? Storage::disk('public')->url($post->cover_image_path)
                : null,
            'published_at' => $post->published_at?->toIso8601String(),
            'updated_at' => $post->updated_at->toIso8601String(),
        ];
    }
}
