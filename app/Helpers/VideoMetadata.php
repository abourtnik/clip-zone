<?php

namespace App\Helpers;

use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe;
use FFMpeg\FFMpeg;

class VideoMetadata
{
    private static FFProbe $FFProbe;
    private static FFMpeg $FFMpeg;

    private static function getFFProbe() {
        if (!isset(self::$FFProbe)) {
            self::$FFProbe = FFProbe::create([
                'ffprobe.binaries' => storage_path('bin/ffprobe')
            ]);
        }
        return self::$FFProbe;
    }

    private static function getFFMpeg() {
        if (!isset(self::$FFMpeg)) {
            self::$FFMpeg = FFMpeg::create([
                'ffmpeg.binaries' => storage_path('bin/ffmpeg'),
                'ffprobe.binaries' => storage_path('bin/ffprobe')
            ]);
        }
        return self::$FFMpeg;
    }

    public static function getDuration(string $path): float {

        return self::getFFProbe()
            ->format($path)
            ->get('duration');
    }

    public static function getDimensions(string $path): Dimension {

        return self::getFFProbe()
            ->streams($path)
            ->videos()
            ->first()
            ->getDimensions();
    }

    public static function extractImage (string $path, $time = 0): string|false
    {
        $video = self::getFFMpeg()->open($path);

        $filename = Number::unique() . '.jpg';

        $filePath = storage_path('app/thumbnails/'). $filename;

        try {
            $frame = $video->frame(TimeCode::fromSeconds($time));
            $frame->save($filePath, true);
            return $filename;
        } catch (\Exception $e) {
            return false;
        }
    }
}
