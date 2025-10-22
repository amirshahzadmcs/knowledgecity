<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Define roles and regions
        $roles = ['student', 'instructor', 'admin'];
        $regions = ['UAE', 'KSA'];

        $users = [];

        // ======================
        // Admin Users
        // ======================
        $users[] = [
            'name' => 'UAE Super Admin',
            'email' => 'admin.uae@knowledgecity.com',
            'password' => Hash::make('Admin123!'),
            'phone' => '+971500000001',
            'role' => 'admin',
            'region' => 'UAE',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        $users[] = [
            'name' => 'KSA Admin',
            'email' => 'admin.ksa@knowledgecity.com',
            'password' => Hash::make('Admin123!'),
            'phone' => '+966500000001',
            'role' => 'admin',
            'region' => 'KSA',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // ======================
        // Instructors (5 per region)
        // ======================
        foreach ($regions as $region) {
            for ($i = 1; $i <= 5; $i++) {
                $users[] = [
                    'name' => $faker->name(),
                    'email' => strtolower($faker->unique()->firstName) . ".inst$i@" . strtolower($region) . ".knowledgecity.com",
                    'password' => Hash::make('Instructor123!'),
                    'phone' => $faker->phoneNumber,
                    'role' => 'instructor',
                    'region' => $region,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // ======================
        // Students (10 per region)
        // ======================
        foreach ($regions as $region) {
            for ($i = 1; $i <= 10; $i++) {
                $users[] = [
                    'name' => $faker->name(),
                    'email' => strtolower($faker->unique()->firstName) . ".student$i@" . strtolower($region) . ".knowledgecity.com",
                    'password' => Hash::make('Student123!'),
                    'phone' => $faker->phoneNumber,
                    'role' => 'student',
                    'region' => $region,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // ======================
        // Insert all users at once
        // ======================
        DB::table('users')->insert($users);
    }
}
