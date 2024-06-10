<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Website;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class PostController extends Controller
{
    public function store(Request $request)
    {

        try {
            $request->validate([
                'title' => 'required|string',
                'description' => 'required|string',
                'website_id' => 'required|integer',
            ]);

            $website = Website::find($request->input('website_id'));
            if (!$website) {
                return response()->json([
                    'message' => 'Website not found',
                ], 404);
            }

            $post = new Post();
            $post->website_id = $request->input('website_id');
            $post->title = $request->input('title');
            $post->description = $request->input('description');
            $post->save();

            return response()->json([
                'message' => 'Post created successfully',
                'data' => $post
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getPaginatedPosts(Request $request)
    {
        try {
            $limit = $request->query('limit', 20);
            $posts = Post::paginate($limit);
            return response()->json($posts);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching posts'
            ], 500);
        }
    }
}
