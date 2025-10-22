<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'amount', 'currency', 'payment_method', 'transaction_id', 'status'
    ];
    public function student() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function course() {
        return $this->belongsTo(Course::class);
    }
}