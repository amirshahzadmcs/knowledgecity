<?php 
namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class AnalyticsHelper
{
    public static function logEvent($userId, $action, $courseId = null, $lessonId = null, $metadata = [])
    {
        try {
            Http::post(env('APP_URL') . '/api/event', [
                'user_id' => $userId,
                'action' => $action,
                'course_id' => $courseId,
                'lesson_id' => $lessonId,
                'metadata' => $metadata,
            ]);
        } catch (\Exception $e) {
            \Log::error("Analytics logging failed: " . $e->getMessage());
        }
    }
}

?>