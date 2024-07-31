<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\User;
use App\Models\Activity;
use App\Models\photo;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['photos', 'user'])->get();
        return response()->json([
            'status' => 'success',
            'data' => $employees->map(function ($employee) {
                return [
                    'id' => $employee->id,
                    'user_id' => $employee->user_id,
                    'first_name' => $employee->user->first_name,
                    'last_name' => $employee->user->last_name,
                    'department' => $employee->department,
                    'phone_number' => $employee->phone_number,
                    'address' => $employee->address,
                    'shift_starts' => $employee->shift_starts,
                    'shift_ends' => $employee->shift_ends,
                    'info_description' => $employee->info_description,
                    'total_score' => $employee->total_score,
                    'birth_date' => $employee->birth_date,
                    'photos' => $employee->photos->pluck('photo_path'),
                ];
            }),
        ], 200);
    }

    public function show(Employee $employee)
    {
        $employee->load(['photos', 'user']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $employee->id,
                'user_id' => $employee->user_id,
                'first_name' => $employee->user->first_name,
                'last_name' => $employee->user->last_name,
                'department' => $employee->department,
                'phone_number' => $employee->phone_number,
                'address' => $employee->address,
                'shift_starts' => $employee->shift_starts,
                'shift_ends' => $employee->shift_ends,
                'info_description' => $employee->info_description,
                'total_score' => $employee->total_score,
                'birth_date' => $employee->birth_date,
                'photos' => $employee->photos->pluck('photo_path'),
            ],
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $profilePhotoPath = null;

            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                $profilePhotoPath = $photos[0]->store('profile_photos', 'public');
            }

            $user = User::create([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email' => $request->input('email'),
                'password' => bcrypt($request->input('password')),
                'profile_photo' => $profilePhotoPath,
            ]);

            $employee = Employee::create([
                'user_id' => $user->id,
                'department' => $request->input('department'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'shift_starts' => $request->input('shift_starts'),
                'shift_ends' => $request->input('shift_ends'),
                'info_description' => $request->input('info_description'),
                'salary' => $request->input('salary'),
                'position' => $request->input('position'),
                'birth_date' => $request->input('birth_date'),
                'total_score' => 0,
            ]);

            if ($request->hasFile('photos')) {
                foreach (array_slice($photos, 1) as $photo) {
                    $filename = time() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('public/' . $employee->nickname, $filename);
                    Photo::create([
                        'employee_id' => $employee->id,
                        'photo_path' => $path,
                    ]);
                }
            }

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
            'department' => 'required|string|max:255',
            'phone_number' => 'required|string|regex:/^[0-9]{10}$/',
            'address' => 'required|string|max:255',
            'shift_starts' => 'required',
            'shift_ends' => 'required',
            'info_description' => 'nullable|string',
            'salary' => 'required|string|regex:/^[0-9]{10}$/',
            'position' => 'required|string|max:255',
            'total_score' => 'required|integer',
            'birth_date' => 'required|date',
            'photos.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $employee->update([
                'department' => $request->input('department'),
                'phone_number' => $request->input('phone_number'),
                'address' => $request->input('address'),
                'shift_starts' => $request->input('shift_starts'),
                'shift_ends' => $request->input('shift_ends'),
                'info_description' => $request->input('info_description'),
                'salary' => $request->input('salary'),
                'position' => $request->input('position'),
                'total_score' => $request->input('total_score'),
                'birth_date' => $request->input('birth_date'),
            ]);

            if ($request->hasFile('photos')) {
                $photos = $request->file('photos');
                $profilePhotoPath = $photos[0]->store('profile_photos', 'public');

                $employee->user->update([
                    'profile_photo' => $profilePhotoPath,
                ]);

                foreach (array_slice($photos, 1) as $photo) {
                    $filename = time() . '.' . $photo->getClientOriginalExtension();
                    $path = $photo->storeAs('public/' . $employee->nickname, $filename);
                    Photo::create([
                        'employee_id' => $employee->id,
                        'photo_path' => $path,
                    ]);
                }
            }

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
        $activities = Activity::where('employee_id', $employee->id)
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
    public function calculateMonthlyPoints(Request $request, $employee_id)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:1900'
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $activities = Activity::where('employee_id', $employee_id)
            ->whereBetween('timing', [$startOfMonth, $endOfMonth])
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

    public function calculateMonthlyPointsForAll(Request $request)
    {
        $request->validate([
            'month' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:1900'
        ]);

        $month = $request->input('month');
        $year = $request->input('year');

        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        $employees = Activity::select('employee_id')
            ->distinct()
            ->get();

        $pointsData = [];

        foreach ($employees as $employee) {
            $activities = Activity::where('employee_id', $employee->employee_id)
                ->whereBetween('timing', [$startOfMonth, $endOfMonth])
                ->with('service')
                ->get();

            $totalPoints = $activities->sum(function ($activity) {
                return $activity->service->points_number;
            });
            $e = Employee::findOrFail($employee->employee_id);
            $pointsData[] = [
                'employee_name' => $e->nickname,
                'total_points' => $totalPoints
            ];
        }

        return response()->json([
            'status' => 'success',
            'data' => $pointsData
        ], 200);
    }
    public function addPhotos(Request $request, $employee_id)
    {
        $employee = Employee::findOrFail($employee_id);

        $request->validate([
            'photos' => 'required|array|min:1',
            'photos.*' => 'required|image|mimes:jpeg,png,jpg',
        ]);

        $photos = [];
        foreach ($request->file('photos') as $photo) {
            $filename = time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public/' . $employee->nickname, $filename);

            $photos[] = Photo::create([
                'employee_id' => $employee->id,
                'photo_path' => 'public/' . $employee->nickname . '/' . $filename,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'photos' => $photos,
        ], 200);
    }

}

