<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Course;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        $user = $request->user('sanctum');
        $payments = Payment::where('user_id', $user->id)->get();
        return response()->json($payments);
    }

    public function pay(Request $request, $enrollmentId)
    {
        $user = $request->user('sanctum');

        $enrollment = Enrollment::find($enrollmentId);
        if (!$enrollment) {
            return response()->json(['message' => 'Enrollment not found'], 404);
        }

        $existingPayment = Payment::where('user_id', $user->id)
            ->where('course_id', $enrollment->course_id)
            ->first();
        if ($existingPayment) {
            return response()->json(['message' => 'Payment already exists'], 400);
        }
        $course = Course::find($enrollment->course_id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        $transactionId = 'trusation' . strtoupper(uniqid());

        $payment = new Payment();
        $payment->user_id = $user->id;
        $payment->course_id = $course->id;
        $payment->amount = $course->price;
        $payment->currency = 'AED';
        $payment->payment_method = 'credit_card';
        $payment->transaction_id = $transactionId;
        $payment->status = 'completed';
        $payment->save();

        return response()->json($payment, 201);
    }
}
