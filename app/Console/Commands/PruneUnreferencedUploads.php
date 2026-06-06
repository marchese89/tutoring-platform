<?php

namespace App\Console\Commands;

use App\Models\Admin;
use App\Models\Certificate;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class PruneUnreferencedUploads extends Command
{
    protected $signature = 'uploads:prune-orphans {--hours=24} {--dry-run}';

    protected $description = 'Delete uploaded files that are old enough and no longer referenced by the database.';

    public function handle(): int
    {
        $hours = max(0, (int) $this->option('hours'));
        $cutoff = now()->subHours($hours)->getTimestamp();
        $dryRun = (bool) $this->option('dry-run');

        $deleted = 0;

        $deleted += $this->pruneDisk(
            'private',
            [
                'lessons/presentations',
                'lessons/files',
                'exercises/trace',
                'exercises/execution',
                'lesson_requests/request_files',
                'lesson_requests/solution_files',
            ],
            $this->referencedPrivatePaths(),
            $cutoff,
            $dryRun
        );

        $deleted += $this->pruneDisk(
            'public',
            [
                'admin/photos',
                'certificates',
            ],
            $this->referencedPublicPaths(),
            $cutoff,
            $dryRun
        );

        $this->info(($dryRun ? 'Would delete ' : 'Deleted ').$deleted.' unreferenced upload files.');

        return self::SUCCESS;
    }

    private function pruneDisk(
        string $disk,
        array $directories,
        Collection $referenced,
        int $cutoff,
        bool $dryRun
    ): int {
        $deleted = 0;
        $storage = Storage::disk($disk);

        foreach ($directories as $directory) {
            foreach ($storage->allFiles($directory) as $path) {
                if ($referenced->contains($path)) {
                    continue;
                }

                if ($storage->lastModified($path) > $cutoff) {
                    continue;
                }

                if (! $dryRun) {
                    $storage->delete($path);
                }

                $deleted++;
            }
        }

        return $deleted;
    }

    private function referencedPrivatePaths(): Collection
    {
        return collect()
            ->merge(Lesson::query()->pluck('presentation_file'))
            ->merge(Lesson::query()->pluck('content_file'))
            ->merge(Exercise::query()->pluck('prompt_file'))
            ->merge(Exercise::query()->pluck('solution_file'))
            ->merge(LessonRequest::query()->pluck('request_file'))
            ->merge(LessonRequest::query()->pluck('solution_file'))
            ->filter()
            ->values();
    }

    private function referencedPublicPaths(): Collection
    {
        return collect()
            ->merge(Admin::query()->pluck('photo_path'))
            ->merge(Certificate::query()->pluck('file_path'))
            ->filter()
            ->map(fn (string $path) => str_starts_with($path, '/storage/')
                ? substr($path, 9)
                : $path)
            ->values();
    }
}
