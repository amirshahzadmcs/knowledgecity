<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserActivity;

class AnalyticsController extends Controller
{
    // Log a user action
    public function logEvent(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer',
            'action' => 'required|string',
            'course_id' => 'nullable|integer',
            'lesson_id' => 'nullable|integer',
            'metadata' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $activity = UserActivity::create([
            'user_id' => $data['user_id'],
            'course_id' => $data['course_id'] ?? null,
            'lesson_id' => $data['lesson_id'] ?? null,
            'action' => $data['action'],
            'metadata' => $data['metadata'] ?? null,
        ]);

        return response()->json($activity, 201);
    }

    public function courseReport($courseId)
    {
        $course = Course::find($courseId);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $report = [
            'course_id' => $course->id,
            'course_title' => $course->title,
            'enrollments' => UserActivity::where('course_id', $courseId)
                            ->where('action', 'course_enroll')
                            ->count(),
            'course_views' => UserActivity::where('course_id', $courseId)
                            ->where('action', 'course_view')
                            ->count(),
            'lessons_completed' => UserActivity::where('course_id', $courseId)
                            ->where('action', 'lesson_complete')
                            ->count(),
            'video_plays' => UserActivity::where('course_id', $courseId)
                            ->where('action', 'video_play')
                            ->count(),
        ];

        return response()->json($report);
    }
}
