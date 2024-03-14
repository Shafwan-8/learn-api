<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OwnerPost
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = auth()->user();
        $post = Post::findOrFail($request->id);

        if ($currentUser->id !== $post->author) {
            return response()->json(['message' => 'Maaf, data yang Anda cari tidak tersedia'], 404);
        }

        return $next($request);
    }
}
