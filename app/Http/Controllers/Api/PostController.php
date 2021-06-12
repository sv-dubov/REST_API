<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Validator;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::all();
        return response()->json($posts);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request_data = $request->only(['title', 'content']);
        $validator = Validator::make($request_data, [
            "title" => ['required', 'string'],
            "content" => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->messages()
            ])->setStatusCode(422);
        }

        $post = Post::create([
            "title" => $request->title,
            "content" => $request->content
        ]);

        return response()->json([
            "status" => true,
            "post" => $post
        ])->setStatusCode(201, "Post was stored");
    }

    public function show($id)
    {
        $post = Post::find($id);
        if ($post) {
            return response()->json($post);

        } else {
            return response()->json([
                "status" => false,
                "message" => "Post not found"
            ])->setStatusCode(404, 'Post not found');
        }
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        $request_data = $request->only(['title', 'content']);
        $validator = Validator::make($request_data, [
            "title" => ['required', 'string'],
            "content" => ['required']
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->messages()
            ])->setStatusCode(422);
        }

        $post = Post::find($id);
        if ($post) {
            $post->title = $request_data["title"];
            $post->content = $request_data["content"];
            $post->save();
            return response()->json([
                "status" => true,
                "message" => "Post was updated"
            ])->setStatusCode(200, "Post was updated");


        } else {
            return response()->json([
                "status" => false,
                "message" => "Post not found"
            ])->setStatusCode(404, "Post not found");
        }
    }

    public function destroy($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->delete();
            return response()->json([
                "status" => true,
                "message" => "Post was deleted"
            ])->setStatusCode(200, "Post was deleted");
        } else {
            return response()->json([
                "status" => false,
                "message" => "Post not found"
            ])->setStatusCode(404, "Post not found");
        }
    }
}
