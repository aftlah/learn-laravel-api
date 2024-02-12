<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {

        // ada 2 cara untuk memanggil relation tabel
        // 1. with('namaRelation:id,username')
        // ketika kita menggukana with() di return kita tidak pelu menggunakan loadmissing()
        // writer adalah relation dari model Post ke Model User
        // $post = Post::with('writer:id,username')->get();

        // tanpa menggunakan loadmissing()
        // return PostDetailResource::collection($post);

        // tanpa menggunakan with()
        $posts = Post::all();

        // 2. loadmissing('namaRelation:id,username')
        // ketika kita menggukana loadmissing maka di $post tidak perlu menggunakan with()
        return PostDetailResource::collection($posts->loadmissing(['writer:id,username', 'comments:id,post_id,user_id,comment']));

    }

    public function show(Post $post)
    {
        // writer adalah relation dari model Post ke Model User
        $post = Post::with('writer:id,username')->findOrFail($post->id);

        // return new PostDetailResource($post);
        return new PostDetailResource($post->loadmissing(['writer:id,username', 'comments:id,post_id,user_id,comment']));
    }

    public function store(Request $request)
    {
        // dd($request->file('image')->hashName());
        $validated = Validator::make($request->all(), [
            'title' => 'required',
            'news_content' => 'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048|file',
        ]);

        if ($validated->fails()) {
            return response()->json($validated->errors(), 400);
        }

        // ini cara untuk mencegah undefined jika tidak mengupload image
        $pathImage = '';
        if ($request->file('file')) {
            $fileName = $request->file('file')->hashName();
            $extension = $request->file('file')->extension();
            $pathImage = $fileName . '.' . $extension;

            Storage::putFileAs('images', $request->file('file'), $pathImage);


        }
        $request['image'] = $pathImage;

        // mengambil author berdasarkan orang yang sedang login
        $request['author'] = Auth::user()->id;


        // $post = Post::create([
        //     'title' => $request->title,
        //     'news_content' => $request->news_content,
        // ]);

        // langsung mengambil semua request
        $post = Post::create($request->all());

        // ini return/tanpa memanggil relsasi tabel users
        // return new PostResource($post);

        //  ini return dengam membawa relasi dari tabel users
        // loadmissing adalah memeriksa apakah hubungan / relation telah dimuat
        return new PostDetailResource($post->loadmissing('writer:id,username'));
    }

    public function update(Request $request, Post $post)
    {
        $validate = Validator::make($request->all(), [
            'title' => 'required',
            'news_content' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json($validate->errors(), 400);

        }

        $post = Post::findOrFail($post->id);
        $post->update($request->all());

        return new PostDetailResource($post->loadmissing('writer:id,username'));


    }

    public function destroy(Post $post)
    {
        $post = Post::findOrFail($post->id);
        $post->delete();

        return new PostDetailResource($post->loadmissing('writer:id,username'));
    }



}


