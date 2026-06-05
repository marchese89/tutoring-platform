<?php

namespace App\Support;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

final class PrivateUploadStorage
{
    public static function store(UploadedFile $file, string $directory): string
    {
        $path = $file->store($directory, 'private');

        if (! is_string($path)) {
            throw new RuntimeException('Unable to store the uploaded file.');
        }

        return $path;
    }

    public static function delete(string|array|null $paths): void
    {
        $paths = array_values(array_filter((array) $paths));

        if ($paths !== []) {
            Storage::disk('private')->delete($paths);
        }
    }
}
