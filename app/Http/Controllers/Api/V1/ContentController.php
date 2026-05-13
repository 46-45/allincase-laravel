<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\ContentPage;

class ContentController extends Controller
{
    public function show(string $slug)
    {
        $page = ContentPage::where('slug', $slug)->first();

        if (!$page) {
            return response()->json(['detail' => "Halaman '{$slug}' tidak ditemukan"], 404);
        }

        return response()->json([
            'slug' => $page->slug,
            'title' => $page->title,
            'content' => $page->content,
            'updated_at' => $page->updated_at?->toIso8601String(),
        ]);
    }
}
