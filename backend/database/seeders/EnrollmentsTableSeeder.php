<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class EnrollmentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch all students
        $students = DB::table('users')->where('role', 'student')->get();

        foreach ($students as $student) {
            // Fetch courses taught by instructors in the same region
            $regionalInstructorIds = DB::table('users')
                ->where('role', 'instructor')
                ->where('region', $student->region)
                ->pluck('id')
                ->toArray();

            $regionalCourses = DB::table('courses')
                ->whereIn('instructor_id', $regionalInstructorIds)
                ->pluck('id')
                ->toArray();

            if (empty($regionalCourses)) {
                // Skip if no courses available in this region
                continue;
            }

            // Number of courses to enroll (max = available courses)
            $numCourses = min(rand(2, 4), count($regionalCourses));

            $coursesToEnroll = $faker->randomElements($regionalCourses, $numCourses);

            foreach ($coursesToEnroll as $courseId) {
                DB::table('enrollments')->insert([
                    'user_id' => $student->id,
                    'course_id' => $courseId,
                    'progress' => $faker->randomFloat(2, 0, 100), // 0%–100%
                    'completed' => false,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}