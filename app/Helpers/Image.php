<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\UploadedFile;

class Image
{
    public static function storeAndDelete(UploadedFile|null $file, string|null $initialFile, string $disk): string|null {

        if ($file && $initialFile && !Str::contains($initialFile, 'default')) {
            Storage::disk($disk)->delete($initialFile);
        }

        return $file?->store('/', $disk) ?? $initialFile;
    }

    public static function deleteIf(string|null $file, string $disk): void {

        if ($file && !Str::contains($file, 'default')) {
            Storage::disk($disk)->delete($file);
        }
    }
}
