<?php

namespace Tests\Feature;

use App\Models\AdminUser;
use App\Models\Booking;
use App\Models\CommunityMember;
use App\Models\Event;
use App\Models\Membership;
use App\Models\MembershipOrder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPanelTest extends TestCase
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

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.clients.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.reports.payments'))->assertRedirect(route('admin.login'));
    }

    public function test_login_page_renders_restricted_access_design(): void
    {
        $this->get(route('admin.login'))
            ->assertOk()
            ->assertSee('Acceso Privado')
            ->assertSee('Área Restringida')
            ->assertSee('Mantener sesión segura')
            ->assertSee('TLS 1.3')
            ->assertSee('Ingresar al Panel de Control');
    }

    public function test_navbar_shows_login_modal_for_guests(): void
    {
        $this->get(route('home'))
            ->assertOk()
            ->assertSee('id="admin-login-modal"', false)
            ->assertSee('data-open-admin-login', false);

        $this->actingAs($this->admin, 'admin')
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('id="admin-login-modal"', false);
    }

    public function test_admin_can_login_with_valid_credentials(): void
    {
        $this->post(route('admin.login.submit'), [
            'email' => 'admin@hibranding.com',
            'password' => 'contraseña-segura',
        ])->assertRedirect(route('admin.dashboard'));

        $this->assertAuthenticatedAs($this->admin, 'admin');
    }

    public function test_dashboard_renders_kpis_and_modules(): void
    {
        $event = Event::create([
            'title' => 'Founder Dinner & Demo Night',
            'category' => 'CENA · NETWORKING',
            'description' => 'Una mesa íntima.',
            'place' => 'Casa Hi, Polanco',
            'starts_at' => now()->addDays(3),
            'price' => 150,
            'capacity' => 50,
            'is_published' => true,
        ]);

        Membership::create([
            'name' => 'Studio',
            'slug' => 'studio',
            'description' => 'Tu base para crecer.',
            'price' => 4200,
            'billing_period' => 'mes',
            'capacity' => 12,
            'featured' => true,
            'features' => ['Escritorio dedicado'],
            'sort_order' => 2,
        ]);

        MembershipOrder::create([
            'reference' => 'MO-TEST0001',
            'membership_slug' => 'studio',
            'membership_name' => 'Studio',
            'membership_price' => 4200,
            'customer_name' => 'Ana López',
            'customer_email' => 'ana@example.com',
            'tax_id' => 'J-00000000-9',
            'country' => 'Venezuela',
            'address' => 'Av. Principal',
            'payment_method' => 'tarjeta',
            'card_last4' => '4242',
            'subtotal' => 4200,
            'discount_amount' => 0,
            'tax_amount' => 798,
            'total' => 4998,
            'status' => MembershipOrder::STATUS_PAID,
        ]);

        Booking::create([
            'event_id' => $event->id,
            'reference' => 'BK-TEST0001',
            'total_amount' => 150,
            'tickets_count' => 1,
            'status' => 'pendiente',
        ]);

        $this->actingAs($this->admin, 'admin')
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Panel Directivo')
            ->assertSee('Membresías activas')
            ->assertSee('Próximos eventos')
            ->assertSee('Pagos pendientes')
            ->assertSee('Gestión de Eventos & Workshops')
            ->assertSee('Precios & Detalles de Membresías')
            ->assertSee('Historial & Gestión de Pagos Recibidos')
            ->assertSee('MO-TEST0001')
            ->assertSee('BK-TEST0001')
            ->assertSee('Marcar pagado');
    }

    public function test_membership_prices_update_in_real_time(): void
    {
        $membership = Membership::create([
            'name' => 'Daylight',
            'slug' => 'daylight',
            'description' => 'Un lugar para empezar.',
            'price' => 1800,
            'billing_period' => 'mes',
            'featured' => false,
            'features' => ['Acceso flexible'],
            'sort_order' => 1,
        ]);

        $this->actingAs($this->admin, 'admin')
            ->putJson(route('admin.memberships.update', $membership), [
                'price' => 1950,
                'capacity' => 25,
                'featured' => true,
            ])
            ->assertOk()
            ->assertJson(['success' => true]);

        $membership->refresh();

        $this->assertSame(1950, $membership->price);
        $this->assertSame(25, $membership->capacity);
        $this->assertTrue($membership->featured);
    }

    public function test_membership_orders_can_be_approved_and_rejected(): void
    {
        $approvedOrder = MembershipOrder::create($this->orderAttributes(['reference' => 'MO-APROBADA']));
        $rejectedOrder = MembershipOrder::create($this->orderAttributes(['reference' => 'MO-RECHAZADA']));

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.membership-orders.approve', $approvedOrder))
            ->assertRedirect();

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.membership-orders.reject', $rejectedOrder))
            ->assertRedirect();

        $this->assertTrue($approvedOrder->fresh()->is_paid);
        $this->assertNotNull($approvedOrder->fresh()->paid_at);
        $this->assertTrue($rejectedOrder->fresh()->is_rejected);
    }

    public function test_processed_orders_cannot_change_status(): void
    {
        $order = MembershipOrder::create($this->orderAttributes([
            'reference' => 'MO-PAGADA',
            'status' => MembershipOrder::STATUS_PAID,
        ]));

        $this->actingAs($this->admin, 'admin')
            ->post(route('admin.membership-orders.approve', $order))
            ->assertSessionHas('error');

        $this->assertSame(MembershipOrder::STATUS_PAID, $order->fresh()->status);
    }

    public function test_payments_report_downloads_as_csv(): void
    {
        MembershipOrder::create($this->orderAttributes(['reference' => 'MO-CSV0001']));

        $response = $this->actingAs($this->admin, 'admin')
            ->get(route('admin.reports.payments'));

        $response->assertOk()
            ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
            ->assertDownload();

        $this->assertStringContainsString('MO-CSV0001', $response->streamedContent());
        $this->assertStringContainsString('Método de pago', $response->streamedContent());
    }

    public function test_sidebar_pages_render_for_authenticated_admins(): void
    {
        CommunityMember::create([
            'name' => 'Lucía Herrera',
            'slug' => 'lucia-herrera',
            'company' => 'Norte Studio',
            'role' => 'Founder & Creative Director',
            'category' => 'Consumer & Creative',
            'initials' => 'LH',
            'tone' => 'clay',
            'email' => 'lucia@example.com',
        ]);

        $this->actingAs($this->admin, 'admin');

        $this->get(route('admin.clients.index'))
            ->assertOk()
            ->assertSee('Clientes &')
            ->assertSee('Lucía Herrera');

        $this->get(route('admin.guide'))
            ->assertOk()
            ->assertSee('Guía')
            ->assertSee('Voz de marca');

        $this->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('Actualizar contraseña')
            ->assertSee('admin@hibranding.com');
    }

    public function test_admin_can_update_password(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->from(route('admin.settings.edit'))
            ->post(route('admin.settings.password'), [
                'current_password' => 'contraseña-segura',
                'password' => 'nueva-contraseña-123',
                'password_confirmation' => 'nueva-contraseña-123',
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHas('success');

        $this->assertTrue(password_verify('nueva-contraseña-123', $this->admin->fresh()->password));
    }

    public function test_admin_cannot_update_password_with_wrong_current_password(): void
    {
        $this->actingAs($this->admin, 'admin')
            ->from(route('admin.settings.edit'))
            ->post(route('admin.settings.password'), [
                'current_password' => 'no-es-la-contraseña',
                'password' => 'nueva-contraseña-123',
                'password_confirmation' => 'nueva-contraseña-123',
            ])
            ->assertSessionHasErrors('current_password');
    }

    private function orderAttributes(array $overrides = []): array
    {
        return array_merge([
            'reference' => 'MO-TEST0001',
            'membership_slug' => 'studio',
            'membership_name' => 'Studio',
            'membership_price' => 4200,
            'customer_name' => 'Ana López',
            'customer_email' => 'ana@example.com',
            'tax_id' => 'J-00000000-9',
            'country' => 'Venezuela',
            'address' => 'Av. Principal',
            'payment_method' => 'tarjeta',
            'card_last4' => '4242',
            'subtotal' => 4200,
            'discount_amount' => 0,
            'tax_amount' => 798,
            'total' => 4998,
            'status' => MembershipOrder::STATUS_PROCESSING,
        ], $overrides);
    }
}
