<?php

namespace App\Services;

use App\Enums\ThumbnailStatus;
use App\Helpers\File;
use App\Models\Video;
use Illuminate\Http\Request;

class ThumbnailService
{
    public static function save (Request $request) : void {

        /** @var Video $video */
        $video = $request->route('video');

        $thumbnailFile = $request->file('thumbnail_file');

        $selected = $request->get('thumbnail');

        if ($thumbnailFile) {

            $file = File::storeAndDelete($thumbnailFile,Video::THUMBNAIL_FOLDER,  $video->uploadedThumbnail?->file);

            $video->thumbnails()->updateOrCreate(
                ['status' => ThumbnailStatus::UPLOADED],
                ['file' => $file]
            );
        }

        if ($video->thumbnail->id != $selected) {
            $video->thumbnail()->update(['is_active' => false]);

            if ($selected === '0') {
                $video->thumbnails()->where('status', ThumbnailStatus::UPLOADED)->update(['is_active' => true]);
            }
            else {
                $video->thumbnails()->where('id', $selected)->update(['is_active' => true]);
            }

        }
    }

}
