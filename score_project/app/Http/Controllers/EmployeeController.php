<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Activity;

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

    public function show(Employee $employee)
    {
        return response()->json([
            'status' => 'success',
            'data' => $employee,
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
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'nickname' => 'required|string|max:255',
            'department' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'birth_date' => 'required|date',
        ]);

        try {
            $user = User::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
            ]);

            $employee = Employee::create([
                'nickname' => $request->input('nickname'),
                'department' => $request->input('department'),
                'phone_number' => $request->input('phone_number'),
                'birth_date' => $request->input('birth_date'),
                'user_id' => $user->id,
            ]);

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
            'department' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'total_score' => 'required|integer',
            'birth_date' => 'required|date',
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
    public function calculatePoints(Request $request, $employee_id)
    {
        $request->validate([
            'date' => 'required|date'
        ]);

        $date = $request->input('date');

        $activities = Activity::where('employee_id', $employee_id)
            ->whereDate('timing', $date)
            ->with('service')
            ->get();

        $totalPoints = $activities->sum(function ($activity) {
            return $activity->service->points_number;
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_points' => $totalPoints
            ],
        ], 200);
    }
    public function calculateTotalPoints(Request $request, $employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $activities = Activity::where('employee_id', $employee_id)
            ->with('service')
            ->get();

        $totalPoints = $activities->sum(function ($activity) {
            return $activity->service->points_number;
        });
        $employee->total_score = $totalPoints;
        $employee->save();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_points' => $totalPoints
            ],
        ], 200);
    }


    public function calculateTotalPointsForAllEmployees()
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $activities = Activity::where('employee_id', $employee->id)
                ->with('service')
                ->get();

            $totalPoints = $activities->sum(function ($activity) {
                return $activity->service->points_number;
            });

            $employee->total_score = $totalPoints;
            $employee->save();
        }

        $sortedEmployees = Employee::select('nickname', 'total_score')
            ->orderBy('total_score', 'desc')
            ->get();


        return response()->json([
            'status' => 'success',
            'data' => $sortedEmployees
        ], 200);
    }

}
