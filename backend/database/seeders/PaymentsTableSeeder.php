<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class PaymentsTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();

        // Fetch all enrollments
        $enrollments = DB::table('enrollments')->get();

        foreach ($enrollments as $enrollment) {

            $status = $faker->randomElement(['pending','completed','failed']);
            $coursePrice = DB::table('courses')
                ->where('id', $enrollment->course_id)
                ->value('price');

            // Generate a unique transaction ID without Faker unique()
            $transactionId = 'TXN' . strtoupper(uniqid()) . rand(100,999);

            DB::table('payments')->insert([
                'user_id' => $enrollment->user_id,
                'course_id' => $enrollment->course_id,
                'amount' => $coursePrice,
                'currency' => $faker->randomElement(['UAE','KSA']),
                'payment_method' => $faker->randomElement(['credit_card','paypal','stripe','bank_transfer']),
                'transaction_id' => $transactionId,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
