<?php

namespace App\Helpers;

use App\Models\Video;
use FFMpeg\Coordinate\Dimension;
use FFMpeg\Coordinate\TimeCode;
use FFMpeg\FFProbe;
use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use Illuminate\Support\Facades\Storage;

class VideoMetadata
{
    public const string VIDEO_EXTENSION = 'mp4';

    private static FFProbe $FFProbe;
    private static FFMpeg $FFMpeg;

    private static function getFFProbe() {
        if (!isset(self::$FFProbe)) {
            self::$FFProbe = FFProbe::create([
                'ffprobe.binaries' => '/usr/bin/ffprobe'
            ]);
        }
        return self::$FFProbe;
    }

    private static function getFFMpeg() {
        if (!isset(self::$FFMpeg)) {
            self::$FFMpeg = FFMpeg::create([
                'ffmpeg.binaries' => '/usr/bin/ffmpeg',
                'ffprobe.binaries' => '/usr/bin/ffprobe'
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

    public static function extractImage (string $path, int $time = 0): string|false
    {
        $video = self::getFFMpeg()->open($path);

        $filename = Number::unique() . '.jpg';

        $filePath = Storage::disk('local')->path(Video::THUMBNAIL_FOLDER.DIRECTORY_SEPARATOR.$filename);

        try {
            $frame = $video->frame(TimeCode::fromSeconds($time));
            $frame->save($filePath, true);
            return $filename;
        } catch (\Exception $e) {
            return false;
        }
    }

    public static function convert(string $relativePath): string
    {
        $absolutePath = Storage::disk('local')->path($relativePath);

        $video = self::getFFMpeg()->open($absolutePath);

        // If video is already in mp4 format return original path
        if (pathinfo($absolutePath, PATHINFO_EXTENSION) !== self::VIDEO_EXTENSION) {

            $newVideoFilename = pathinfo($absolutePath, PATHINFO_FILENAME).'.' .self::VIDEO_EXTENSION;

            $newVideoPath = Video::VIDEO_FOLDER.DIRECTORY_SEPARATOR.$newVideoFilename;

            $format = new X264();

            // Fix for error "Encoding failed : Can't save to X264"
            // See: https://github.com/PHP-FFMpeg/PHP-FFMpeg/issues/310
            $format->setAudioCodec("libmp3lame");

            // Save the video in the same directory with the new format
            $video->save($format, Storage::disk('local')->path($newVideoPath));

            // Remove original file
            Storage::disk('local')->delete($relativePath);
        }

        return $newVideoPath ?? $relativePath;
    }
}
