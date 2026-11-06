<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Event;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentMethodManagementTest extends TestCase
{
    use RefreshDatabase;

    private AdminUser $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = AdminUser::create([
            'name' => 'Administradora Hi',
            'email' => 'admin@hibranding.com',
            'password' => 'contraseña-segura',
        ]);
    }

    public function test_admin_can_manage_payment_methods(): void
    {
        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.payment-methods.index'))
            ->assertOk()
            ->assertSee('Métodos de');

        $this->post(route('admin.payment-methods.store'), [
            'name' => 'Zelle',
            'type' => PaymentMethod::TYPE_ZELLE,
            'account_number' => 'pagos@hibranding.com',
            'account_holder' => 'Hi Branding LLC',
            'is_active' => '1',
        ])->assertRedirect();

        $method = PaymentMethod::where('name', 'Zelle')->firstOrFail();

        $this->get(route('admin.payment-methods.index'))
            ->assertOk()
            ->assertSee('Zelle');

        $this->post(route('admin.payment-methods.toggle', $method));
        $this->assertFalse($method->fresh()->is_active);

        $this->put(route('admin.payment-methods.update', $method), [
            'name' => 'Zelle Internacional',
            'type' => PaymentMethod::TYPE_ZELLE,
            'account_number' => 'pagos@hibranding.com',
            'account_holder' => 'Hi Branding LLC',
            'is_active' => '1',
        ]);

        $this->assertDatabaseHas('payment_methods', ['id' => $method->id, 'name' => 'Zelle Internacional']);

        $this->delete(route('admin.payment-methods.destroy', $method));
        $this->assertDatabaseMissing('payment_methods', ['id' => $method->id]);
    }

    public function test_admin_can_assign_payment_methods_to_event(): void
    {
        $this->actingAs($this->admin, 'admin');

        $zelle = PaymentMethod::create(['name' => 'Zelle', 'type' => 'zelle', 'account_number' => 'pagos@hi.com', 'account_holder' => 'Hi LLC', 'is_active' => true]);
        $binance = PaymentMethod::create(['name' => 'Binance Pay', 'type' => 'binance_pay', 'account_number' => '123456', 'account_holder' => 'HiBranding', 'is_active' => true]);
        $inactive = PaymentMethod::create(['name' => 'Método viejo', 'type' => 'otro', 'is_active' => false]);

        $this->get(route('admin.events.create'))
            ->assertOk()
            ->assertSee('name="payment_methods[]"', false)
            ->assertSee('Zelle')
            ->assertSee('Binance Pay')
            ->assertDontSee('Método viejo')
            ->assertDontSee('Datos bancarios para transferencia');

        $response = $this->post(route('admin.events.store'), [
            'title' => 'Cena de fundadores',
            'category' => 'CENA',
            'description' => 'Encuentro privado.',
            'place' => 'Casa Hi',
            'starts_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'price' => 150,
            'capacity' => 40,
            'payment_methods' => [$zelle->id],
        ]);

        $response->assertRedirect(route('admin.events.index'));

        $event = Event::where('title', 'Cena de fundadores')->firstOrFail();

        $this->assertDatabaseHas('event_payment_method', ['event_id' => $event->id, 'payment_method_id' => $zelle->id]);
        $this->assertDatabaseMissing('event_payment_method', ['event_id' => $event->id, 'payment_method_id' => $binance->id]);

        $this->get(route('admin.events.edit', $event))
            ->assertOk()
            ->assertSee('Métodos de pago de este evento');

        $this->put(route('admin.events.update', $event), [
            'title' => 'Cena de fundadores',
            'category' => 'CENA',
            'description' => 'Encuentro privado.',
            'place' => 'Casa Hi',
            'starts_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'price' => 150,
            'capacity' => 40,
            'payment_methods' => [$binance->id, $zelle->id],
        ]);

        $this->assertCount(2, $event->paymentMethods()->get());

        $this->put(route('admin.events.update', $event), [
            'title' => 'Cena de fundadores',
            'category' => 'CENA',
            'description' => 'Encuentro privado.',
            'place' => 'Casa Hi',
            'starts_at' => now()->addDays(7)->format('Y-m-d\TH:i'),
            'price' => 150,
            'capacity' => 40,
            'payment_methods' => [$inactive->id],
        ])->assertSessionHasErrors();
    }

    public function test_event_without_assigned_methods_falls_back_to_all_active(): void
    {
        PaymentMethod::create(['name' => 'Pago Móvil', 'type' => 'pago_movil', 'bank' => 'Banco de Venezuela', 'account_number' => '0412-1234567', 'account_holder' => 'Hi Branding C.A.', 'tax_id' => 'J-40123456-7', 'is_active' => true, 'sort_order' => 1]);
        PaymentMethod::create(['name' => 'Método oculto', 'type' => 'otro', 'account_number' => '0000', 'account_holder' => 'Nadie', 'is_active' => false, 'sort_order' => 2]);

        $event = Event::create([
            'title' => 'Workshop de marca',
            'category' => 'WORKSHOP',
            'description' => 'Sesión intensiva',
            'place' => 'Casa Hi',
            'starts_at' => now()->addDays(3),
            'price' => 80,
            'capacity' => 20,
            'is_published' => true,
        ]);

        $this->get(route('events.show', $event))
            ->assertOk()
            ->assertSee('Pago Móvil')
            ->assertSee('0412-1234567')
            ->assertSee('Hi Branding C.A.')
            ->assertSee('J-40123456-7')
            ->assertDontSee('Método oculto');
    }
}
