<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::with(['employee', 'service'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $activities,
        ], 200);
    }

    public function acknowledge(Activity $activity)
    {
        $activity->update(['status' => 'acknowledged']);
        return response()->json([
            'status' => 'success',
            'message' => 'Activity acknowledged successfully.'
        ], 200);
    }

    public function deny(Activity $activity)
    {
        $activity->update(['status' => 'denied']);
        return response()->json([
            'status' => 'success',
            'message' => 'Activity denied successfully.'
        ], 200);
    }
}
