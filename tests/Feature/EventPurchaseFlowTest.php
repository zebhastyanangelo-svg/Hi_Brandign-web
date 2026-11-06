<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EventPurchaseFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_detail_and_checkout_flow_work(): void
    {
        Storage::fake('public');

        $paymentMethod = PaymentMethod::create([
            'name' => 'Transferencia Banesco',
            'type' => PaymentMethod::TYPE_TRANSFERENCIA,
            'bank' => 'Banesco',
            'tax_id' => 'J-40123456-7',
            'account_number' => '0134-0000-00-00000000',
            'account_holder' => 'Hi Branding C.A.',
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $event = Event::create([
            'title' => 'Noche de marca en comunidad',
            'category' => 'SEMINARIO',
            'description' => 'Un encuentro para hablar de crecimiento y narrativa.',
            'place' => 'Casa Hi, Polanco',
            'starts_at' => now()->addDays(5)->setTime(19, 30),
            'price' => 150.00,
            'capacity' => 80,
            'is_published' => true,
        ]);

        $event->paymentMethods()->attach($paymentMethod);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('Noche de marca en comunidad')
            ->assertSee('Comprar entrada')
            ->assertSee('Transferencia Banesco')
            ->assertSee('0134-0000-00-00000000');

        $response = $this->postJson(route('events.checkout', $event), [
            'tickets' => 2,
            'attendees' => [
                ['first_name' => 'Ana', 'last_name' => 'López', 'document' => 'V-12345678', 'email' => 'ana@example.com'],
                ['first_name' => 'Luis', 'last_name' => 'Pérez', 'document' => 'V-87654321', 'email' => 'luis@example.com'],
            ],
            'payment_method_id' => $paymentMethod->id,
            'payment_reference' => 'REF-001',
            'payment_date' => now()->format('Y-m-d'),
            'proof' => UploadedFile::fake()->image('comprobante.png'),
        ]);

        $response->assertOk()->assertJson(['success' => true]);

        $this->assertDatabaseHas('bookings', [
            'event_id' => $event->id,
            'tickets_count' => 2,
            'total_amount' => 300.00,
            'status' => 'pendiente',
            'payment_method_id' => $paymentMethod->id,
        ]);

        $this->assertDatabaseCount('attendees', 2);
    }

    public function test_checkout_rejects_payment_method_not_assigned_to_event(): void
    {
        Storage::fake('public');

        $allowed = PaymentMethod::create([
            'name' => 'Zelle', 'type' => PaymentMethod::TYPE_ZELLE,
            'account_number' => 'pagos@hi.com', 'account_holder' => 'Hi LLC',
            'is_active' => true,
        ]);
        $other = PaymentMethod::create([
            'name' => 'Binance Pay', 'type' => PaymentMethod::TYPE_BINANCE,
            'account_number' => '123', 'account_holder' => 'Hi',
            'is_active' => true,
        ]);

        $event = Event::create([
            'title' => 'Workshop de marca',
            'category' => 'WORKSHOP',
            'description' => 'Sesión intensiva.',
            'place' => 'Casa Hi',
            'starts_at' => now()->addDays(3),
            'price' => 100.00,
            'capacity' => 10,
            'is_published' => true,
        ]);

        $event->paymentMethods()->attach($allowed);

        $response = $this->postJson(route('events.checkout', $event), [
            'tickets' => 1,
            'attendees' => [
                ['first_name' => 'Ana', 'last_name' => 'López', 'document' => 'V-1', 'email' => 'ana@example.com'],
            ],
            'payment_method_id' => $other->id,
            'payment_reference' => 'REF-002',
            'payment_date' => now()->format('Y-m-d'),
            'proof' => UploadedFile::fake()->image('comprobante.png'),
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['payment_method_id']);
    }
}
