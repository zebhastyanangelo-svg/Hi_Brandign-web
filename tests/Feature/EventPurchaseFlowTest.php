<?php

namespace Tests\Feature;

use App\Models\Event;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_detail_and_checkout_flow_work(): void
    {
        $event = Event::create([
            'title' => 'Noche de marca en comunidad',
            'slug' => 'noche-de-marca-en-comunidad',
            'category' => 'SEMINARIO',
            'description' => 'Un encuentro para hablar de crecimiento y narrativa.',
            'place' => 'Casa Hi, Polanco',
            'event_date' => now()->addDays(5)->setTime(19, 30),
            'price' => 1500,
            'capacity' => 80,
            'bank_name' => 'Banco de Comercio',
            'account_number' => '0134-0001-12345678',
            'account_holder' => 'Hi Branding C.A.',
            'tax_id' => 'J-00000000-9',
            'image' => 'events/default.jpg',
            'is_published' => true,
        ]);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('Comprar Entrada')
            ->assertSee('Cuenta regresiva')
            ->assertSee('Noche de marca en comunidad');

        $this->post(route('events.checkout.store', $event), [
            'quantity' => 2,
            'email' => 'ana@example.com',
            'full_name' => 'Ana López',
            'document' => 'V-12345678',
            'payment_method' => 'transferencia',
            'payment_reference' => 'REF-001',
            'attendees' => [
                ['full_name' => 'Ana López', 'document' => 'V-12345678', 'email' => 'ana@example.com'],
                ['full_name' => 'Luis Pérez', 'document' => 'V-87654321', 'email' => 'luis@example.com'],
            ],
        ])->assertRedirect(route('events.show', $event));

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'email' => 'ana@example.com',
            'status' => 'pending_payment',
        ]);
    }
}
