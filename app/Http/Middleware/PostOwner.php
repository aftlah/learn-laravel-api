<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PostOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // mengambil id post yang dikirim melalui request
        $post = Post::findOrFail($request->post->id);

        // mengambil id user yang sedang login
        $currentUser = Auth::user();

        // jika author != user id yang sedang login
        if($post->author != $currentUser->id){
            return response()->json([
                'message' => 'Data nit found'
            ]);
        }


        // return response()->json($post);

        return $next($request);
    }
}
