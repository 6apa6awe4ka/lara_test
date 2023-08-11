<?php

namespace App\Http\Controllers;

use App\Http\Resources\ArticleCollection;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    const ERROR_CODE_VALIDATION_FAILED = 1;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Article::query();

        if ($views = (int)$request->get('views')) {
            $query->where('views', '>=', $views);
        }

        if ($product_id = (int)$request->get('product_id')) {
            $query->where('product_id', $product_id);
        }

        if ($date_from = (int)$request->get('date_from')) {
            $query->where('time_create', '>=', date('Y-m-d H:i:s', $date_from));
        }

        if ($date_to = (int)$request->get('date_to')) {
            $query->where('time_create', '<=', date('Y-m-d H:i:s', $date_to));
        }

        $collection = $query
            ->orderBy('time_create', 'desc')
            ->offset((int)$request->get('offset') ?: 0)
            ->limit((int)$request->get('limit') ?: 10)
            ->get();

        return response()->json($collection->toArray());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $href)
    {
        $article = Article::where('href', $href)
            ->first();

        $article->views++;

        return response()->json($article);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $title = (string)$request->get('title');
        $body = (string)$request->get('body');
        $description = (string)$request->get('description');

        $article = Article::where('id', $id)
            ->first();

        if ($title) {
            if (strlen($title) > 255) {
                return response()->json([
                    'error' => static::ERROR_CODE_VALIDATION_FAILED,
                ]);
            }

            $article->title = $title;
        }

        if ($body) {
            $article->body = $body;
        }

        if ($description) {
            $article->description = $description;
        }

        $article->save();

        return response()->json($article);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
