<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            // fungsi exist ini untuk mengecek apakan post_id ada di dalam tabel post, collomn id
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required'
        ]);

        $request['user_id'] = auth()->user()->id;

        $comment = Comment::create($request->all());

        return new CommentResource($comment->loadmissing(['commentator:id,username']));

    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $validated = $request->validate([
            'comment' => 'required',
        ]);

        $comment = Comment::findOrFail($id);
        $comment->update($request->only('comment'));

        // $comment->update($validated);
        return new CommentResource($comment->loadmissing(['commentator:id,username']));


    }

    public function destroy($id)
    {
        $comment = Comment::findOrFail($id);

        $comment->delete();

        return response()->json([
            'message' => 'Comment Deleted',
        ], 200);
    }
}
