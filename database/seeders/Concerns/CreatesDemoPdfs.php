<?php

namespace Database\Seeders\Concerns;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

trait CreatesDemoPdfs
{
    private function storeDemoPdf(string $path, string $title, array $lines): string
    {
        $dompdf = new Dompdf;
        $escapedTitle = e($title);
        $generatedAt = now()->format('d/m/Y H:i');
        $items = collect($lines)
            ->map(fn (string $line) => '<li>'.e($line).'</li>')
            ->implode('');

        $dompdf->loadHtml(<<<HTML
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{$escapedTitle}</title>
    <style>
        body { color: #1f2937; font-family: DejaVu Sans, sans-serif; font-size: 14px; line-height: 1.5; margin: 48px; }
        h1 { color: #0f766e; font-size: 26px; margin-bottom: 8px; }
        .meta { color: #6b7280; font-size: 12px; margin-bottom: 32px; }
        .box { border: 1px solid #d1d5db; border-radius: 8px; padding: 20px; }
        li { margin-bottom: 10px; }
    </style>
</head>
<body>
    <h1>{$escapedTitle}</h1>
    <div class="meta">Demo teaching document generated at {$generatedAt}</div>
    <div class="box"><ul>{$items}</ul></div>
</body>
</html>
HTML);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        Storage::disk('private')->put($path, $dompdf->output());

        return $path;
    }
}
