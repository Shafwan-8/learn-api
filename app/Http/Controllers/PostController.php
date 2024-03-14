<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();

        // return response()->json(['data' => $posts]);

        return PostDetailResource::collection($posts->loadMissing('writer:id,username'));
    }

    public function show($id) {
        $post = Post::with('writer:id,email,username')->findOrFail($id);

        return new PostDetailResource($post);
    }

    public function store(Request $request) {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $request->request->add(['author' => auth()->user()->id]);
        $post = Post::create($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required',
            'news_content' => 'required',
        ]);

        $post = Post::findOrFail($id);

        $post->update($request->all());

        return new PostDetailResource($post->loadMissing('writer:id,username'));

    }

    public function destroy($id) {
        $post = Post::findOrFail($id)->delete();
        
        // return new PostDetailResource($post->loadMissing('writer:id,username'));
        return response()->json(['message' => 'Post deleted successfully'], 200);
    }
}
