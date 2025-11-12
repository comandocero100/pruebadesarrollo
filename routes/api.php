<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\UserCourseController;
use App\Http\Controllers\MeController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth.token')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Student self-service
    Route::get('/me/courses', [MeController::class, 'myCourses']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // CRUD Alumnos (students)
        Route::get('/students', [StudentController::class, 'index']);
        Route::post('/students', [StudentController::class, 'store']);
        Route::get('/students/{id}', [StudentController::class, 'show']);
        Route::put('/students/{id}', [StudentController::class, 'update']);
        Route::delete('/students/{id}', [StudentController::class, 'destroy']);

        // CRUD Cursos
        Route::get('/courses', [CourseController::class, 'index']);
        Route::post('/courses', [CourseController::class, 'store']);
        Route::get('/courses/{id}', [CourseController::class, 'show']);
        Route::put('/courses/{id}', [CourseController::class, 'update']);
        Route::delete('/courses/{id}', [CourseController::class, 'destroy']);

        // Asignación de cursos a un usuario
        Route::get('/users/{id}/courses', [UserCourseController::class, 'index']);
        Route::post('/users/{id}/courses', [UserCourseController::class, 'attach']);
        Route::delete('/users/{id}/courses/{courseId}', [UserCourseController::class, 'detach']);
    });
});

