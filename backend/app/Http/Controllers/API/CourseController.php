<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CourseController extends Controller
{

    public function index(Request $request)
    {

        $user = $request->user('sanctum');

        if ($user->role === 'instructor') {
            $courses = Course::where('instructor_id', $user->id)->get();
        } elseif ($user->role === 'student') {
            $courses = Course::where('is_published', true)->get();
        } elseif ($user->role === 'admin') {
            $courses = Course::all();
        } else {
            $courses = [];
        }

        return response()->json($courses);
    }

    public function show($id)
    {
        $course = Course::with(['lessons.video', 'instructor'])->find($id);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $user = request()->user('sanctum');
        if ($user && $user->role === 'student') {
            AnalyticsHelper::logEvent($user->id, 'course_view', $course->id);
        }

        return response()->json($course);
    }
}