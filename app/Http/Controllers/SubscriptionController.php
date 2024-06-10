<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Models\Subscription;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|string',
                'website_id' => 'required|integer',
            ]);

            $website = Website::find($request->input('website_id'));
            if (!$website) {
                return response()->json([
                    'message' => 'Website not found',
                ], 404);
            }

            $subscriber = Subscriber::firstOrCreate(['email' => $request->input('email')]);

            $subscription = Subscription::where('subscriber_id', $subscriber->id)
                ->where('website_id', $website->id)
                ->first();

            if ($subscription) {
                return response()->json([
                    'message' => 'User already subscribed to this website',
                ], 409);
            }

            $subscription = new Subscription;
            $subscription->subscriber_id = $subscriber->id;
            $subscription->website_id = $website->id;
            $subscription->save();

            return response()->json([
                'message' => 'Subscription created successfully',
                'data' => $subscription
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation Failed',
                'errors' => $e->errors()
            ], 422);
        }
    }
}
