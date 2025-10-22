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

    // Store a new course
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'instructor_id' => 'required|exists:users,id',
            'slug' => 'nullable|string|unique:courses,slug',
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'language' => 'nullable|string|max:50',
            'level' => 'nullable|string|max:50',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $thumbnailUrl = null;
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/thumbnails', $filename);
            $thumbnailUrl = url('storage/thumbnails/' . $filename);
        }
        $course = Course::create([
            'title' => $request->title,
            'instructor_id' => $request->instructor_id,
            'slug' => $request->slug ?? Str::slug($request->title) . '-' . time(),
            'description' => $request->description,
            'category' => $request->category,
            'language' => $request->language,
            'level' => $request->level,
            'thumbnail_url' => $thumbnailUrl,
            'price' => $request->price ?? 0,
            'is_published' => true,
        ]);

        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course
        ], 201);
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