<?php

use App\Http\Controllers\ActiveAccountController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeroomTeacherController;
use App\Http\Controllers\MeController;
use App\Http\Controllers\OtherDataController;
use App\Http\Controllers\PresentController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SkippingClassController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('/attendance');
});

Route::get('/attendance', [PresentController::class, 'index']);
Route::get('/attendance-home', [PresentController::class, 'returnHome']);

Route::group(['prefix' => 'admin'], function () {
    // ADMIN AND TEACHER
    Route::middleware('guest:user,teacher')->group(function () {
        Route::get('/login', [AuthController::class, 'login'])->name('login');
        Route::post('/login', [AuthController::class, 'authenticate']);
    });

    Route::middleware(['auth:user,teacher'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::get('/home', [HomeController::class, 'index']);

        Route::get('/change-password', [MeController::class, 'changePassword']);
        Route::put('/change-password', [MeController::class, 'updatePassword']);

        Route::get('/change-pic', [MeController::class, 'changePic']);
        Route::put('/change-pic', [MeController::class, 'updatePic']);

        Route::resource('/attendances', AttendanceController::class)->only(['index', 'update', 'edit']);
        Route::get('/attendances/rekap', [AttendanceController::class, 'rekapIndex']);
        Route::get('/attendances/rekap/{nisn}', [AttendanceController::class, 'rekapShow']);
        Route::get('/attendances/grade-rekap', [AttendanceController::class, 'rekapGradeIndex']);
        Route::get('/attendances/grade-rekap/{slug}', [AttendanceController::class, 'rekapGradeShow']);

        Route::get('/attendances/export', [AttendanceController::class, 'exportExcel']);
        Route::get('/skipping-class/export', [SkippingClassController::class, 'exportExcel']);

        Route::resource('/skipping-class', SkippingClassController::class)->only(['index', 'create', 'store', 'destroy']);
    });

    // TEACHER
    Route::middleware(['auth:teacher'])->group(function () {
        Route::get('/myschedules', [ScheduleController::class, 'mySchedule']);
    });

    // ADMIN
    Route::middleware(['auth:user'])->group(function () {
        Route::get('/students/export', [StudentController::class, 'exportExcel']);
        Route::get('/teachers/export', [TeacherController::class, 'exportExcel']);

        Route::get('/other-data', [OtherDataController::class, 'index']);
        Route::get('/other-data/{id}/edit', [OtherDataController::class, 'edit']);
        Route::put('/other-data/{id}', [OtherDataController::class, 'update']);

        Route::resource('/grades', GradeController::class)->except('show');

        Route::resource('/teachers', TeacherController::class);

        Route::resource('/subjects', SubjectController::class)->except('show');

        Route::resource('/homeroom-teachers', HomeroomTeacherController::class)->except('show');

        Route::resource('/schedules', ScheduleController::class)->except('show');

        Route::resource('/students', StudentController::class);

        Route::resource('/admins', UserController::class)->except(['show']);

        Route::get('/active-account', [ActiveAccountController::class, 'index']);
        Route::delete('/active-account/{nisn}', [ActiveAccountController::class, 'delete']);
    });
});
