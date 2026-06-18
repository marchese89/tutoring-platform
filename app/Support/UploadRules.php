<?php

namespace App\Support;

final class UploadRules
{
    public static function pdf(): array
    {
        return [
            'required',
            'file',
            'mimes:pdf',
            'mimetypes:application/pdf',
            'max:'.config('uploads.pdf_max_kilobytes'),
        ];
    }

    public static function image(): array
    {
        return [
            'required',
            'image',
            'mimes:jpg,jpeg,png,webp',
            'max:'.config('uploads.image_max_kilobytes'),
        ];
    }
}
