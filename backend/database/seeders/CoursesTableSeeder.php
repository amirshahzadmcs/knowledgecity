<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class CoursesTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch all instructors
        $instructors = DB::table('users')->where('role', 'instructor')->get();

        // Example course titles per category for realism
        $courseTemplates = [
            'Math' => ['Algebra Fundamentals', 'Calculus Basics', 'Geometry for Beginners'],
            'Science' => ['Introduction to Physics', 'Chemistry 101', 'Biology Made Easy'],
            'Programming' => ['Learn PHP from Scratch', 'Mastering Laravel', 'JavaScript Essentials'],
            'Language' => ['English Grammar Basics', 'Arabic for Beginners', 'Conversational French'],
            'Business' => ['Digital Marketing 101', 'Entrepreneurship Basics', 'Financial Literacy']
        ];

        foreach ($instructors as $instructor) {
            // Each instructor creates 2 courses
            for ($i = 1; $i <= 2; $i++) {

                // Randomly pick a category and title
                $category = $faker->randomElement(array_keys($courseTemplates));
                $title = $faker->randomElement($courseTemplates[$category]);
                $slug = strtolower(str_replace(' ', '-', $title)) . '-12' . $i.rand(1, 1000) ;

                $courseId = DB::table('courses')->insertGetId([
                    'instructor_id' => $instructor->id,
                    'title' => $title,
                    'slug' => $slug,
                    'description' => $faker->paragraph(4),
                    'category' => $category,
                    'language' => $faker->randomElement(['English', 'Arabic']),
                    'level' => $faker->randomElement(['beginner', 'intermediate', 'advanced']),
                    'thumbnail_url' => 'https://picsum.photos/seed/' . rand(1, 1000) . '/400/250',
                    'price' => $faker->randomFloat(2, 20, 150),
                    'is_published' => $faker->boolean(80),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Generate meaningful lesson titles
                $lessonTitles = [
                    'Introduction & Overview',
                    'Core Concepts',
                    'Practical Application'
                ];

                foreach ($lessonTitles as $index => $lessonTitle) {
                    DB::table('course_lessons')->insert([
                        'course_id' => $courseId,
                        'title' => $lessonTitle,
                        'video_id' => null,
                        'duration' => rand(600, 1200), // 10 to 20 minutes
                        'order_no' => $index + 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        }
    }

}
