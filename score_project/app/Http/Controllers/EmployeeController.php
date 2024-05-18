<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::all();
        return response()->json([
            'status' => 'success',
            'data' => $employees,
        ], 200);
    }

    public function create()
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Create employee form data'
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nickname' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'total_score' => 'required|integer',
        ]);

        try {
            $employee = Employee::create($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $employee,
                'message' => 'Employee created successfully.'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error creating employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Employee $employee)
    {
        return response()->json([
            'status' => 'success',
            'data' => $employee
        ], 200);
    }

    public function update(Request $request, Employee $employee)
    {
        $request->validate([
            'nickname' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'total_score' => 'required|integer',
        ]);

        try {
            $employee->update($request->all());
            return response()->json([
                'status' => 'success',
                'data' => $employee,
                'message' => 'Employee updated successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error updating employee: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Employee $employee)
    {
        try {
            $employee->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Employee deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error deleting employee: ' . $e->getMessage()
            ], 500);
        }
    }
}
