<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RouteSmokeTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_top_level_pages_render(): void
    {
        $this->assertRoutesRender([
            'home',
            'about',
            'privacy-policy',
            'cookie-policy',
            'theme-areas.index',
            'lesson-requests.create',
            'lesson-requests.success',
            'login',
            'register',
            'password.request',
            'registration.success',
            'registration.error',
        ]);
    }

    public function test_student_top_level_pages_render(): void
    {
        $student = Student::factory()->create();
        $this->actingAs($student->user);

        $this->assertRoutesRender([
            'student.dashboard',
            'student.account',
            'student.account.profile',
            'student.account.credentials',
            'student.courses.index',
            'student.direct-requests.index',
            'student.direct-requests.purchased',
            'student.orders.index',
            'student.invoices.index',
            'student.review',
            'cart.show',
            'checkout.show',
            'payment.extra',
            'payment.complete',
            'payment.ok',
        ]);
    }

    public function test_admin_top_level_pages_render(): void
    {
        $admin = Admin::factory()->create();
        $this->actingAs($admin->user);

        $this->assertRoutesRender([
            'admin.dashboard',
            'admin.account',
            'admin.account.profile',
            'admin.account.credentials',
            'admin.account.photo',
            'admin.account.address',
            'admin.account.certificates.index',
            'admin.account.certificates.create',
            'admin.account.vat-number',
            'admin.teaching.index',
            'admin.theme-areas.index',
            'admin.subjects.index',
            'admin.courses.index',
            'admin.courses.create',
            'admin.students.index',
            'admin.lesson-requests.index',
            'admin.chats.index',
            'admin.sales.index',
            'admin.invoices.index',
            'admin.invoices.extra',
            'admin.invoices.created',
        ]);
    }

    private function assertRoutesRender(array $routeNames): void
    {
        foreach ($routeNames as $routeName) {
            $response = $this->get(route($routeName));

            $this->assertSame(200, $response->status(), "Route [{$routeName}] did not render successfully.");
        }
    }
}
