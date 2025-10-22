<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'original_file', 'processed_file', 'status', 'resolution', 'format', 'size_mb', 'duration'
    ];

    public function course() {
        return $this->belongsTo(Course::class);
    }
    public function uploader() {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function lesson() {
        return $this->hasOne(CourseLesson::class, 'video_id');
    }
}
