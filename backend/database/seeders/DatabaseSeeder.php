<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Call your other seeders here
        $this->call(UsersTableSeeder::class);
        $this->call(CoursesTableSeeder::class);
        $this->call(VideosTableSeeder::class);
        $this->call(EnrollmentsTableSeeder::class);
        $this->call(PaymentsTableSeeder::class);

    }
}
