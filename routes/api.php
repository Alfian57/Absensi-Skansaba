<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MeController;
use App\Http\Controllers\Api\PresentController;
use App\Http\Controllers\Api\ScheduleController;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('/student/login', [AuthController::class, 'login']);

Route::get('/schedules/{studentId}', [ScheduleController::class, 'getClassSchedules']);

Route::get('/attendances', [PresentController::class, 'getAttendances']);
Route::get('/attendances/{grade}', [PresentController::class, 'getAttendancesWithGrade']);
Route::get('/attendances-home', [PresentController::class, 'getAttendancesHome']);
Route::get('/attendances-home/{grade}', [PresentController::class, 'getAttendancesHomeWithGrade']);

Route::middleware('auth:sanctum')->prefix('student')->group(function () {
    Route::get('/me', [MeController::class, 'profile']);
    Route::get('/my-attendance', [PresentController::class, 'recap']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/change-password', [MeController::class, 'changePassword']);
    Route::post('/present', [PresentController::class, 'store']);
    Route::get('/my-schedules', [ScheduleController::class, 'index']);
});
