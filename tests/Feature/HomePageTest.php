<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\Review;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomePageTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_page_presents_primary_services_and_review_data(): void
    {
        Admin::factory()->create([
            'photo_path' => '/files/tutor.jpg',
        ]);

        $student = Student::factory()->create();
        Review::factory()->create([
            'student_id' => $student->id,
            'rating' => 5,
            'review' => 'Spiegazione chiara e metodo efficace.',
        ]);

        $response = $this->get(route('home'));

        $response->assertOk()
            ->assertSee('Lezioni private di informatica')
            ->assertSee('Esplora i corsi')
            ->assertSee('Richiedi materiale')
            ->assertSee('Spiegazione chiara e metodo efficace.')
            ->assertSee('Sono laureato magistrale in Ingegneria Informatica')
            ->assertSee('images/computer-science-tutoring-hero.jpg', false)
            ->assertSee('data-home-reveal', false)
            ->assertDontSee('Sono un ingegnere informatico')
            ->assertSee(asset('files/tutor.jpg'), false);
    }
}
