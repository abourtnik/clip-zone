<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class Image
{
    public static function storeAndDelete(UploadedFile|null $file, string|null $initialFile, string $path): string|null {

        if ($file && $initialFile) {
            Storage::delete($path.'/'.$initialFile);
        }

        $file?->store($path);

        return $file?->hashName() ?? $initialFile;
    }

    public static function deleteIf(string|null $file, string $path): void {

        if ($file) {
            Storage::delete($path.'/'.$file);
        }
    }
}
