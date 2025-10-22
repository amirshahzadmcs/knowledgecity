<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\CourseController;
use App\Http\Controllers\API\EnrollmentController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\VideoController;
use App\Http\Controllers\API\CourseLessonController;
use App\Http\Controllers\AnalyticsController;

Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);


Route::middleware('auth.api')->group(function(){

    Route::post('/logout',[AuthController::class,'logout']);
    Route::get('/me',[AuthController::class,'me']);

    Route::get('/courses', [CourseController::class,'index']);       // List courses
    Route::get('/courses/{id}', [CourseController::class,'show']);  // Course details

    Route::get('/enrollments', [EnrollmentController::class,'index']);          // List user enrollments
    Route::post('/enroll/{courseId}', [EnrollmentController::class,'enroll']); // Enroll in a course
    Route::put('/enrollment/{id}/progress', [EnrollmentController::class,'updateProgress']); // Update progress


    Route::get('/payments', [PaymentController::class,'index']);              // List user payments
    Route::post('/pay/{enrollmentId}', [PaymentController::class,'pay']);    // Make a payment for enrollment

    Route::get('/course/{courseId}/videos', [VideoController::class,'index']);      // List videos for course
    Route::get('/video/{videoId}/stream', [VideoController::class,'stream']);       // Stream a video
    Route::get('/video/{videoId}/status', [VideoController::class,'status']);       // Video processing status

    Route::post('/courses', [CourseController::class,'store']);        // Create course
    Route::put('/courses/{id}', [CourseController::class,'update']);   // Update course
    Route::delete('/courses/{id}', [CourseController::class,'destroy']); // Delete course

    Route::post('/lessons', [CourseLessonController::class,'store']);   // Create lesson
    Route::put('/lessons/{id}', [CourseLessonController::class,'update']); // Update lesson
    Route::delete('/lessons/{id}', [CourseLessonController::class,'destroy']); // Delete lesson

    Route::post('/event', [AnalyticsController::class, 'logEvent']);
    Route::get('/course/{courseId}/analytics', [AnalyticsController::class, 'courseReport']);


});
