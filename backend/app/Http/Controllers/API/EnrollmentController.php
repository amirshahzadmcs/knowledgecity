<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Enrollment;
use App\Models\Course;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user('sanctum');
        $enrollments = Enrollment::where('user_id', $user->id)->get();
        return response()->json($enrollments);
    }

    public function enroll(Request $request, $courseId)
    {
        $user = $request->user('sanctum');
        if ($user->role !== 'student') {
            return response()->json(['message'=>'Only students can enroll'], 403);
        }

        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $alreadyEnrolled = Enrollment::where('user_id', $user->id)
                            ->where('course_id', $courseId)
                            ->first();

        if ($alreadyEnrolled) {
            return response()->json(['message' => 'Already enrolled'], 400);
        }

        $enrollment = new Enrollment();
        $enrollment->user_id = $user->id;
        $enrollment->course_id = $courseId;
        $enrollment->progress = 0;
        $enrollment->completed = false;
        $enrollment->save();

        // Log enrollment
        AnalyticsHelper::logEvent($user->id, 'course_enroll', $courseId);

        return response()->json($enrollment, 201);
    }
    public function updateProgress(Request $request, $enrollmentId)
    {
        $enrollment = Enrollment::find($enrollmentId);

        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }
        if (!$request->has('progress') || !$request->has('completed')) {
            return response()->json(['message' => 'Missing required fields'], 422);
        }

        $enrollment->progress = $request->progress;
        $enrollment->completed = $request->completed;
        $enrollment->save();

        // Log lesson completion if marked completed
        if ($request->completed) {
            AnalyticsHelper::logEvent(
                $enrollment->user_id,
                'lesson_complete',
                $enrollment->course_id,
                $request->lesson_id ?? null
            );
        }

        return response()->json($enrollment);
    }
}