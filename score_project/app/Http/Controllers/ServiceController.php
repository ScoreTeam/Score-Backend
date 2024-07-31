<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Service;

class ServiceController extends Controller
{
    public function index()
    {
        $services = Service::all();
        return response()->json([
            'status' => 'success',
            'data' => $services,
        ], 200);
    }

    public function create()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Create service form data'
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_number' => 'required|integer',
            'duration_minutes' => 'required|integer',
        ]);

        try {
            $service = Service::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $service,
                'message' => 'Service created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Service $service)
    {
        return response()->json([
            'status' => 'success',
            'data' => $service
        ], 200);
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'points_number' => 'required|integer',
            'duration_minutes' => 'required|integer',
        ]);

        try {
            $service->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $service,
                'message' => 'Service updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating service: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Service $service)
    {
        try {
            $service->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Service deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting service: ' . $e->getMessage()
            ], 500);
        }
    }
}
