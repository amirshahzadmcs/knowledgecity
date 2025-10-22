<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class VideosTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch all lessons
        $lessons = DB::table('course_lessons')->get();

        foreach ($lessons as $lesson) {

            $instructorId = DB::table('courses')
                ->where('id', $lesson->course_id)
                ->value('instructor_id');

            // Insert video
            $videoId = DB::table('videos')->insertGetId([
                'user_id' => $instructorId,
                'course_id' => $lesson->course_id,
                'original_file' => 'videos/original/video_' . $lesson->id . '.mp4',
                'processed_file' => null,
                'status' => 'uploaded',
                'resolution' => '1080p',
                'format' => 'mp4',
                'size_mb' => rand(50, 500), 
                'duration' => rand(300, 1800),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update lesson to link video
            DB::table('course_lessons')
                ->where('id', $lesson->id)
                ->update(['video_id' => $videoId]);
        }
    }
}