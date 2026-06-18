<?php

namespace App\Providers;

use App\Models\Chat;
use App\Models\Exercise;
use App\Models\Lesson;
use App\Models\LessonRequest;
use App\Policies\ChatPolicy;
use App\Policies\ExercisePolicy;
use App\Policies\LessonPolicy;
use App\Policies\LessonRequestPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Chat::class => ChatPolicy::class,
        Exercise::class => ExercisePolicy::class,
        Lesson::class => LessonPolicy::class,
        LessonRequest::class => LessonRequestPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
