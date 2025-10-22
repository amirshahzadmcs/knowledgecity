<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'instructor_id', 'title', 'slug', 'description', 'category',
        'language', 'level', 'thumbnail_url', 'price', 'is_published'
    ];

    public function instructor() {
        return $this->belongsTo(User::class, 'instructor_id');
    }
    public function lessons() {
        return $this->hasMany(CourseLesson::class);
    }
    public function videos() {
        return $this->hasMany(Video::class);
    }
    public function enrollments() {
        return $this->hasMany(Enrollment::class);
    }
    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
