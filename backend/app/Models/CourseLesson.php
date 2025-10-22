<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseLesson extends Model
{
    protected $fillable = [
        'course_id', 'title', 'video_id', 'duration', 'order_no'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function video() {
        return $this->belongsTo(Video::class);
    }
}
