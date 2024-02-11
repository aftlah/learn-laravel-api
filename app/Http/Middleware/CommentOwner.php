<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CommentOwner
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        // dd($comment->user_id);
        $user = Auth::user();
        $comment = Comment::findOrFail($request->id);

        if($user->id != $comment->user_id){
            return response()->json([
                'message' => 'Data not found'
            ],404);
        };
        return $next($request);
    }
}
