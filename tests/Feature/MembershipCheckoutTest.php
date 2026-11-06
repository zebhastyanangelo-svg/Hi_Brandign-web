<?php

namespace Tests\Feature;

use App\Models\IncomeNotification;
use App\Models\Membership;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MembershipCheckoutTest extends TestCase
{
    use RefreshDatabase;

    private Membership $membership;

    private PaymentMethod $paymentMethod;

    protected function setUp(): void
    {
        parent::setUp();

        $this->membership = Membership::create([
            'name' => 'Class Sala Executive',
            'slug' => 'class-sala',
            'description' => 'Ideal para eventos corporativos y talleres de alto nivel.',
            'price' => 1800,
            'billing_period' => 'mes',
            'capacity' => 30,
            'featured' => true,
            'features' => ['85” Smart TV & Audio Pro', 'Wi-Fi de alta velocidad'],
            'sort_order' => 1,
        ]);

        $this->paymentMethod = PaymentMethod::create([
            'name' => 'Pago Móvil',
            'type' => PaymentMethod::TYPE_PAGO_MOVIL,
            'bank' => 'Banco de Venezuela',
            'tax_id' => 'J-40123456-7',
            'account_number' => '0412-123-45-67',
            'account_holder' => 'Hi Branding C.A.',
            'is_active' => true,
            'sort_order' => 1,
        ]);
    }

    public function test_checkout_page_renders_selected_plan_and_payment_methods(): void
    {
        $this->get(route('memberships.checkout', $this->membership->slug))
            ->assertOk()
            ->assertSee('Reporta tu')
            ->assertSee('Class Sala Executive')
            ->assertSee('Pago Móvil')
            ->assertSee('Banco de Venezuela')
            ->assertSee('Tasa BCV del día')
            ->assertSee('Equivalente en bolívares')
            ->assertSee('ENVIAR COMPROBANTE');
    }

    public function test_unknown_membership_slug_aborts(): void
    {
        $this->get(route('memberships.checkout', 'plan-inexistente'))->assertNotFound();
    }

    public function test_payment_notification_creates_pending_income_notification(): void
    {
        Storage::fake('public');

        $response = $this->post(route('memberships.checkout.notify', $this->membership->slug), [
            'customer_name' => 'Ana López',
            'customer_email' => 'ana@example.com',
            'customer_phone' => '+58 412 1234567',
            'payment_method_id' => $this->paymentMethod->id,
            'bank_reference' => '009876543210',
            'payment_date' => now()->format('Y-m-d'),
            'account_holder_name' => 'Ana López',
            'account_holder_relationship' => IncomeNotification::RELATIONSHIP_OWN,
            'proof_path' => UploadedFile::fake()->image('comprobante.png'),
        ]);

        $notification = IncomeNotification::query()->sole();

        $response->assertRedirect(route('memberships.checkout.confirmation', $notification->reference));

        $this->assertDatabaseHas('income_notifications', [
            'id' => $notification->id,
            'membership_slug' => 'class-sala',
            'payment_method_id' => $this->paymentMethod->id,
            'payment_method_name' => 'Pago Móvil',
            'customer_name' => 'Ana López',
            'customer_email' => 'ana@example.com',
            'bank_reference' => '009876543210',
            'status' => IncomeNotification::STATUS_PENDING,
            'amount_usd' => 1800.00,
        ]);

        Storage::disk('public')->assertExists($notification->proof_path);

        $this->get(route('memberships.checkout.confirmation', $notification->reference))
            ->assertOk()
            ->assertSee($notification->reference)
            ->assertSee('Class Sala Executive')
            ->assertSee('Pago Móvil');
    }

    public function test_payment_notification_requires_proof_and_valid_method(): void
    {
        $this->from(route('memberships.checkout', $this->membership->slug))
            ->post(route('memberships.checkout.notify', $this->membership->slug), [
                'customer_name' => 'Ana López',
                'customer_email' => 'ana@example.com',
                'customer_phone' => '+58 412 1234567',
                'payment_method_id' => 9999,
                'bank_reference' => '',
                'payment_date' => now()->addDays(2)->format('Y-m-d'),
                'account_holder_name' => '',
                'account_holder_relationship' => 'invalido',
            ])
            ->assertRedirect(route('memberships.checkout', $this->membership->slug))
            ->assertSessionHasErrors([
                'payment_method_id',
                'bank_reference',
                'payment_date',
                'account_holder_name',
                'account_holder_relationship',
                'proof_path',
            ]);

        $this->assertDatabaseCount('income_notifications', 0);
    }
}
