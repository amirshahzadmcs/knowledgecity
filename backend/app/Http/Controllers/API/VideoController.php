<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Video;
use App\Models\Course;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    public function index($courseId)
    {
        $course = Course::find($courseId);

        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        $lessons = $course->lessons;

        $result = [];

        foreach ($lessons as $lesson) {
            $video = $lesson->video;
            $result[] = [
                'lesson_id' => $lesson->id,
                'lesson_title' => $lesson->title,
                'video' => $video ? [
                'id' => $video->id,
                'original_file' => $video->original_file,
                'processed_file' => $video->processed_file,
                'status' => $video->status,
                'resolution' => $video->resolution,
                'format' => $video->format,
                'duration' => $video->duration,
                ] : null,
            ];
        }

        return response()->json($result);
    }
    public function stream($videoId)
    {
        $video = Video::find($videoId);

        if (!$video || !$video->processed_file) {
            return response()->json(['message' => 'Video not available'], 404);
        }

        $path = $video->processed_file;
        if (!Storage::exists($path)) {
            return response()->json(['message' => 'File not found'], 404);
        }

        $user = request()->user('sanctum');
        if ($user && $user->role === 'student') {
            AnalyticsHelper::logEvent(
                $user->id,
                'video_play',
                $video->course_id,
                $video->lesson_id,
                ['video_id' => $video->id]
            );
        }

        return response()->streamDownload(function () use ($path) {
            echo Storage::get($path);
        }, basename($path));
    }

    public function status($videoId)
    {
        $video = Video::find($videoId);

        if (!$video) {
            return response()->json(['message' => 'Video not found'], 404);
        }
        return response()->json([
            'video_id' => $video->id,
            'status' => $video->status,
            'processed_file' => $video->processed_file,
        ]);
    }
}
