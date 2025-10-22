<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_lessons', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->index();
            $table->string('title', 255);
            $table->unsignedBigInteger('video_id')->nullable()->index();
            $table->integer('duration')->nullable();
            $table->integer('order_no')->default(1);
            $table->timestamps();

            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('video_id')->references('id')->on('videos')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_lessons');
    }
};
