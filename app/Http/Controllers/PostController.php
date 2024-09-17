<?php

namespace App\Http\Controllers;

use App\Filters\PostFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Posts\StorePostsRequest;
use App\Models\Post;
use App\Services\PostServices\StorePostService\StorePostService;
use Spatie\QueryBuilder\QueryBuilder;

class PostController extends Controller
{
    public function approved()
    {
        $post = QueryBuilder::for(Post::class)
        ->allowedFilters((new PostFilter)->filter())
        ->with('worker:id,name')
        ->where('status', 'approved')
        ->get(['id', 'content', 'price', 'worker_id']);
        
        return response()->json([
            'posts' => $post
        ]);
    }

    public function store(StorePostsRequest $request){
        return (new StorePostService())->store($request);
    }

    public function show($id)
    {
        $posts = Post::find($id)->makeHidden('status');
        return response()->json([
            'posts' => $posts
        ]);
    }
}
