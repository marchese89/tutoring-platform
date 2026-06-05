<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

final class PublicUploadStorage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'public');

        return '/storage/'.$path;
    }

    public static function delete(?string $path): void
    {
        if (! $path) {
            return;
        }

        if (str_starts_with($path, '/storage/')) {
            Storage::disk('public')->delete(substr($path, 9));

            return;
        }

        if (str_starts_with($path, '/files/')) {
            File::delete(public_path(ltrim($path, '/')));
        }
    }
}
