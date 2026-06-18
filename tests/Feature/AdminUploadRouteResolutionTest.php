<?php

namespace Tests\Feature;

use Illuminate\Http\Request;
use Tests\TestCase;

class AdminUploadRouteResolutionTest extends TestCase
{
    public function test_lesson_upload_session_routes_are_not_captured_by_lesson_destroy_route(): void
    {
        $this->assertRouteName('DELETE', '/admin/lessons/upload-presentation', 'admin.lessons.upload-presentation.destroy');
        $this->assertRouteName('DELETE', '/admin/lessons/upload-file', 'admin.lessons.upload-file.destroy');
    }

    public function test_exercise_upload_session_routes_are_not_captured_by_exercise_destroy_route(): void
    {
        $this->assertRouteName('DELETE', '/admin/exercises/trace/session', 'admin.exercises.trace.session.destroy');
        $this->assertRouteName('DELETE', '/admin/exercises/execution/session', 'admin.exercises.execution.session.destroy');
    }

    private function assertRouteName(string $method, string $uri, string $expectedName): void
    {
        $request = Request::create($uri, $method);
        $route = app('router')->getRoutes()->match($request);

        $this->assertSame($expectedName, $route->getName());
    }
}
