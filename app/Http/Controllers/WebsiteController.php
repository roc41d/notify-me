<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class WebsiteController extends Controller
{
    public function store(Request $request)
    {

        try {
            $request->validate([
                'name' => 'required|string',
                'url' => 'required|string|url',
            ]);

            $website = new Website();
            $website->name = $request->input('name');
            $website->url = $request->input('url');
            $website->save();

            return response()->json([
                'message' => 'Website created successfully',
                'data' => $website
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
    }

    public function getPaginatedWebsites(Request $request)
    {
        try {
            $limit = $request->query('limit', 20);
            $posts = Website::paginate($limit);
            return response()->json($posts);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'An error occurred while fetching websites'
            ], 500);
        }
    }
}
