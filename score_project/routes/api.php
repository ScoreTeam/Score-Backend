<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\AuthController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login'])->name("login");

Route::middleware('auth:api')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::resource('employees', EmployeeController::class);

    Route::resource('services', ServiceController::class);

    Route::get('/employees/{employee_id}/calculate-points', [EmployeeController::class, 'calculatePoints']);

    Route::get('/employees/{employee_id}/total-points', [EmployeeController::class, 'calculateTotalPoints']);

    Route::get('/employees/total-points/all-employees', [EmployeeController::class, 'calculateTotalPointsForAllEmployees']);

    Route::post('/employees/{employee_id}/calculate-points/monthly', [EmployeeController::class, 'calculateMonthlyPoints']);

    Route::post('/employees/total-points/all-employees/monthly', [EmployeeController::class, 'calculateMonthlyPointsForAll']);
});

Route::get('/test1', [EmployeeController::class, 'getAllEmployeesNamesAndIds'])->name("get All Employees Names And Ids");
