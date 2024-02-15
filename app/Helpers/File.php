<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;

class File
{
    public static function storeAndDelete(UploadedFile|null $file, string $path, string|null $initialFile = null): string|null {

        if ($file && $initialFile) {
            Storage::delete($path.'/'.$initialFile);
        }

        $file?->store($path);

        return $file?->hashName() ?? $initialFile;
    }

    public static function deleteIfExist(string|null $file, string $path): void {

        if ($file) {
            Storage::delete($path.'/'.$file);
        }
    }
}
