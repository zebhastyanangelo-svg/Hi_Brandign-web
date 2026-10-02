<?php

namespace Tests\Feature;

use App\Models\CommunityMember;
use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PortalPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_brand_pages_render(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('animate__animated animate__swing')
            ->assertSee('Donde el Talento')
            ->assertSee('Class Sala')
            ->assertSee('bg-[#FDFBF7]')
            ->assertSee('font-sans')
            ->assertSee('font-serif')
            ->assertSee('flex items-center')
            ->assertSee('space-card-grid grid')
            ->assertSee('https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css', false)
            ->assertSee('id="intro-loader"', false)
            ->assertSee('data-story-hero', false)
            ->assertSee('story-hero-image', false)
            ->assertSee('story-space-finder', false)
            ->assertSee('¿Qué espacio necesitas?')
            ->assertSee('p5-hero', false)
            ->assertSee('p5-badge', false);

        $this->get(route('memberships.index'))
            ->assertOk()
            ->assertSee('Espacios diseñados para')
            ->assertSee('Consultar Membresía');

        $this->get(route('location.index'))
            ->assertOk()
            ->assertSee('Horarios de Atención')
            ->assertSee('p5-badge', false);

        $this->get(route('community.index'))
            ->assertOk()
            ->assertSee('Donde el Capital')
            ->assertSee('Lucía Herrera')
            ->assertSee('href="'.route('community.index').'"', false)
            ->assertSee('grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl mx-auto my-12')
            ->assertSee('animate__animated animate__flipInY')
            ->assertSee('p5-network', false);

        $this->get(route('events.index'))
            ->assertOk()
            ->assertSee('Founder Dinner');

        $this->get(route('appointments.index'))
            ->assertOk()
            ->assertSee('Próximas Sesiones');
    }

    public function test_vite_assets_use_the_forwarded_codespaces_origin(): void
    {
        $this->withServerVariables([
            'HTTP_X_FORWARDED_HOST' => 'fuzzy-disco-jj6wvjgjq5qpcq7qq-8000.app.github.dev',
            'HTTP_X_FORWARDED_PROTO' => 'https',
            'HTTP_X_FORWARDED_PORT' => '443',
        ])->get(route('home'))
            ->assertOk()
            ->assertSee('https://fuzzy-disco-jj6wvjgjq5qpcq7qq-8000.app.github.dev/build/assets/app-', false)
            ->assertDontSee('http://localhost:8000/build/assets/app-', false);
    }

    public function test_appointment_request_is_validated_and_persisted(): void
    {
        $this->post(route('appointments.store'), [
            'name' => 'Ana López',
            'email' => 'ana@example.com',
            'focus' => 'Conocer los espacios',
            'scheduled_at' => now()->addDay()->format('Y-m-d\TH:i'),
            'meeting_type' => 'in-person',
            'notes' => 'Quiero conocer el espacio.',
        ])->assertRedirect(route('appointments.index'));

        $this->assertDatabaseHas('appointments', [
            'email' => 'ana@example.com',
            'focus' => 'Conocer los espacios',
            'meeting_type' => 'in-person',
        ]);
    }

    public function test_appointment_request_requires_contact_and_schedule_details(): void
    {
        $this->post(route('appointments.store'), [])
            ->assertSessionHasErrors(['name', 'email', 'focus', 'scheduled_at', 'meeting_type']);
    }

    public function test_community_and_event_pages_render_database_records(): void
    {
        CommunityMember::create([
            'name' => 'Nadia Torres',
            'slug' => 'nadia-torres',
            'company' => 'Lado Sur',
            'role' => 'Founder',
            'category' => 'Creative',
            'initials' => 'NT',
            'tone' => 'clay',
            'email' => 'nadia@example.com',
        ]);

        Event::create([
            'title' => 'Creative founders breakfast',
            'category' => 'DESAYUNO · COMUNIDAD',
            'description' => 'Una conversación para compartir ideas.',
            'place' => 'Casa Hi, Polanco',
            'starts_at' => now()->addDays(5),
            'is_featured' => true,
            'is_published' => true,
        ]);

        $this->get(route('community.index'))
            ->assertOk()
            ->assertSee('Nadia Torres')
            ->assertDontSee('Lucía Herrera');

        $this->get(route('events.index'))
            ->assertOk()
            ->assertSee('Creative founders breakfast')
            ->assertDontSee('Founder Dinner');
    }
}
