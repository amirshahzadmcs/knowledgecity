<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessVideo implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $video = Video::find($this->videoId);
        if (!$video) return;

        // Example using ffmpeg
        $formats = ['mp4','webm'];
        $resolutions = ['720p','1080p'];

        foreach ($formats as $format) {
            foreach ($resolutions as $res) {
                $outputPath = "videos/processed/{$video->id}_{$res}.{$format}";
                // Run ffmpeg conversion (pseudo-code)
                FFMpeg::fromDisk($video->storage_disk)
                    ->open($video->original_file)
                    ->export()
                    ->toDisk($video->storage_disk)
                    ->inFormat($format)
                    ->resize($res)
                    ->save($outputPath);
            }
        }

        $video->processed_file = $outputPath;
        $video->status = 'completed';
        $video->save();
    }

}
