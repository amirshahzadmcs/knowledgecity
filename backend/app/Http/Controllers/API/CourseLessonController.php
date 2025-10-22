<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseLesson;
use Illuminate\Support\Facades\Validator;

class CourseLessonController extends Controller
{

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'title' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $lesson = new CourseLesson();
        $lesson->course_id = $request->course_id;
        $lesson->title = $request->title;
        $lesson->video_id = $request->video_id;
        $lesson->duration = $request->duration;
        $lesson->order_no = $request->order_no;
        $lesson->save();

        return response()->json($lesson, 201);
    }

    public function update(Request $request, $id)
    {
        $lesson = CourseLesson::find($id);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }

        $lesson->title = $request->title ?? $lesson->title;
        $lesson->video_id = $request->video_id ?? $lesson->video_id;
        $lesson->duration = $request->duration ?? $lesson->duration;
        $lesson->order_no = $request->order_no ?? $lesson->order_no;
        $lesson->save();

        return response()->json($lesson);
    }

    public function destroy($id)
    {
        $lesson = CourseLesson::find($id);

        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }

        $lesson->delete();

        return response()->json(['message' => 'Lesson deleted']);
    }
}
