<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Event;
use App\Models\Membership;
use App\Models\MembershipOrder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DashboardDemoSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->seedMembershipOrders();
        $this->seedDraftEvent();
        $this->seedBookings();
    }

    private function seedMembershipOrders(): void
    {
        $orders = [
            ['reference' => 'MO-DEMO01A', 'membership_slug' => 'daylight', 'customer_name' => 'Camila Duarte', 'customer_email' => 'camila.duarte@example.com', 'tax_id' => 'J-40123456-7', 'country' => 'Colombia', 'city' => 'Bogotá', 'address' => 'Calle 85 #12-40, Of. 302', 'payment_method' => 'paypal', 'paypal_email' => 'camila.duarte@example.com', 'status' => MembershipOrder::STATUS_PAID, 'discount_percent' => 0, 'days_ago' => 5],
            ['reference' => 'MO-DEMO02B', 'membership_slug' => 'studio', 'customer_name' => 'Mateo Ríos', 'customer_email' => 'mateo.rios@example.com', 'tax_id' => 'J-40223456-8', 'country' => 'México', 'city' => 'Ciudad de México', 'address' => 'Av. Presidente Masaryk 111, Polanco', 'payment_method' => 'transferencia', 'transfer_reference' => 'TRF-77412-BBVA', 'status' => MembershipOrder::STATUS_PROCESSING, 'discount_percent' => 0, 'days_ago' => 4],
            ['reference' => 'MO-DEMO03C', 'membership_slug' => 'house', 'customer_name' => 'Valentina Cruz', 'customer_email' => 'valentina.cruz@example.com', 'tax_id' => 'J-40323456-9', 'country' => 'España', 'city' => 'Madrid', 'address' => 'Calle Serrano 45, 3ºB', 'payment_method' => 'tarjeta', 'card_last4' => '9017', 'status' => MembershipOrder::STATUS_PAID, 'discount_percent' => 0, 'days_ago' => 3],
            ['reference' => 'MO-DEMO04D', 'membership_slug' => 'daylight', 'customer_name' => 'Diego Montes', 'customer_email' => 'diego.montes@example.com', 'tax_id' => 'J-40423456-0', 'country' => 'Chile', 'city' => 'Santiago', 'address' => 'Av. Apoquindo 4500, Of. 701', 'payment_method' => 'tarjeta', 'card_last4' => '3382', 'status' => MembershipOrder::STATUS_REJECTED, 'discount_percent' => 0, 'days_ago' => 3, 'admin_notes' => 'Pago rechazado por el banco emisor. Se solicitó un medio de pago alternativo.'],
            ['reference' => 'MO-DEMO05E', 'membership_slug' => 'studio', 'customer_name' => 'Andrés Soler', 'customer_email' => 'andres.soler@example.com', 'tax_id' => 'J-40523456-1', 'country' => 'Argentina', 'city' => 'Buenos Aires', 'address' => 'Arroyo 878, Piso 4', 'payment_method' => 'transferencia', 'transfer_reference' => 'TRF-88213-BBVA', 'status' => MembershipOrder::STATUS_PROCESSING, 'discount_percent' => 10, 'discount_code' => 'HIBRAND10', 'days_ago' => 2],
            ['reference' => 'MO-DEMO06F', 'membership_slug' => 'house', 'customer_name' => 'Lucía Herrera', 'customer_email' => 'lucia.herrera@example.com', 'tax_id' => 'J-40623456-2', 'country' => 'México', 'city' => 'Guadalajara', 'address' => 'Av. Chapultepec 580, Int. 12', 'payment_method' => 'paypal', 'paypal_email' => 'lucia.herrera@example.com', 'status' => MembershipOrder::STATUS_PAID, 'discount_percent' => 0, 'days_ago' => 1],
        ];

        foreach ($orders as $data) {
            $membership = Membership::where('slug', $data['membership_slug'])->firstOrFail();
            $amounts = $this->orderAmounts((int) $membership->price, $data['discount_percent'] ?? 0);
            $paidAt = $data['status'] === MembershipOrder::STATUS_PAID ? now()->subDays($data['days_ago']) : null;

            Model::unguarded(function () use ($data, $membership, $amounts, $paidAt) {
                MembershipOrder::updateOrCreate(
                    ['reference' => $data['reference']],
                    [
                        'membership_slug' => $membership->slug,
                        'membership_name' => $membership->name,
                        'membership_price' => (int) $membership->price,
                        'customer_name' => $data['customer_name'],
                        'customer_email' => $data['customer_email'],
                        'tax_id' => $data['tax_id'],
                        'country' => $data['country'],
                        'city' => $data['city'],
                        'address' => $data['address'],
                        'payment_method' => $data['payment_method'],
                        'card_last4' => $data['card_last4'] ?? null,
                        'paypal_email' => $data['paypal_email'] ?? null,
                        'transfer_reference' => $data['transfer_reference'] ?? null,
                        'discount_code' => $amounts['discount_amount'] > 0 ? $data['discount_code'] ?? null : null,
                        'subtotal' => $amounts['subtotal'],
                        'discount_amount' => $amounts['discount_amount'],
                        'tax_amount' => $amounts['tax_amount'],
                        'total' => $amounts['total'],
                        'status' => $data['status'],
                        'paid_at' => $paidAt,
                        'admin_notes' => $data['admin_notes'] ?? null,
                        'created_at' => now()->subDays($data['days_ago']),
                        'updated_at' => now()->subDays($data['days_ago']),
                    ]
                );
            });
        }
    }

    private function seedDraftEvent(): void
    {
        Event::updateOrCreate(
            ['title' => 'Ritual de marca: propósito y posición'],
            [
                'category' => 'WORKSHOP · ESTRATEGIA',
                'description' => 'Una sesión intensiva para definir el propósito de tu marca y traducirlo en decisiones de posicionamiento concretas.',
                'place' => 'Casa Hi, Polanco',
                'starts_at' => now()->addDays(35)->setTime(10, 0),
                'price' => 180.00,
                'capacity' => 25,
                'is_featured' => false,
                'is_published' => false,
            ]
        );
    }

    private function seedBookings(): void
    {
        $bookings = [
            ['reference' => 'BK-DEMO001', 'event_title' => 'Founder Dinner & Demo Night', 'tickets' => 2, 'status' => 'aprobado', 'days_ago' => 4, 'attendees' => [['Ana', 'Torres', 'V-11222333'], ['Iker', 'Salas', 'V-33222444']]],
            ['reference' => 'BK-DEMO002', 'event_title' => 'Founder Dinner & Demo Night', 'tickets' => 3, 'status' => 'aprobado', 'days_ago' => 4, 'attendees' => [['Luis', 'Márquez', 'V-44333555'], ['Sofía', 'Peña', 'V-55444666'], ['Raúl', 'Gómez', 'V-66555777']]],
            ['reference' => 'BK-DEMO003', 'event_title' => 'Founder Dinner & Demo Night', 'tickets' => 2, 'status' => 'pendiente', 'days_ago' => 1, 'attendees' => [['Elena', 'Vidal', 'V-77666888'], ['Nora', 'Campos', 'V-88777999']]],
            ['reference' => 'BK-DEMO004', 'event_title' => 'Construir una marca que perdure', 'tickets' => 3, 'status' => 'aprobado', 'days_ago' => 3, 'attendees' => [['Pablo', 'Cárdenas', 'V-99888111'], ['Alicia', 'Rey', 'V-11999222'], ['Bruno', 'Díaz', 'V-22111333']]],
            ['reference' => 'BK-DEMO005', 'event_title' => 'Construir una marca que perdure', 'tickets' => 2, 'status' => 'pendiente', 'days_ago' => 1, 'attendees' => [['Marta', 'Ledesma', 'V-33222555'], ['Iván', 'Soto', 'V-44333666']]],
            ['reference' => 'BK-DEMO006', 'event_title' => 'Capital con propósito', 'tickets' => 2, 'status' => 'aprobado', 'days_ago' => 2, 'attendees' => [['Javier', 'Peña', 'V-55444777'], ['Olga', 'Ferrer', 'V-66555888']]],
            ['reference' => 'BK-DEMO007', 'event_title' => 'Capital con propósito', 'tickets' => 1, 'status' => 'rechazado', 'days_ago' => 2, 'admin_notes' => 'El comprobante de pago no es legible. Se pidió reenviar la constancia.', 'attendees' => [['Tomás', 'Aguirre', 'V-77666999']]],
            ['reference' => 'BK-DEMO008', 'event_title' => 'Product office hours', 'tickets' => 1, 'status' => 'aprobado', 'days_ago' => 1, 'attendees' => [['Nadia', 'Fuentes', 'V-88777111']]],
            ['reference' => 'BK-DEMO009', 'event_title' => 'Ritual de marca: propósito y posición', 'tickets' => 2, 'status' => 'pendiente', 'days_ago' => 0, 'attendees' => [['Sergio', 'Bravo', 'V-99888222'], ['Ada', 'Molina', 'V-11999333']]],
        ];

        foreach ($bookings as $data) {
            $event = Event::where('title', $data['event_title'])->firstOrFail();
            $approvedAt = $data['status'] === 'aprobado' ? now()->subDays($data['days_ago'])->addHours(6) : null;

            $booking = Model::unguarded(function () use ($data, $event, $approvedAt) {
                return Booking::updateOrCreate(
                    ['reference' => $data['reference']],
                    [
                        'event_id' => $event->id,
                        'total_amount' => (float) $event->price * $data['tickets'],
                        'tickets_count' => $data['tickets'],
                        'status' => $data['status'],
                        'payment_reference' => 'TRF-'.substr($data['reference'], -6),
                        'payment_date' => now()->subDays($data['days_ago'])->toDateString(),
                        'proof_path' => null,
                        'admin_notes' => $data['admin_notes'] ?? null,
                        'approved_at' => $approvedAt,
                        'created_at' => now()->subDays($data['days_ago']),
                        'updated_at' => now()->subDays($data['days_ago']),
                    ]
                );
            });

            $booking->attendees()->delete();
            foreach ($data['attendees'] as [$firstName, $lastName, $document]) {
                $booking->attendees()->create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'document' => $document,
                    'email' => Str::slug("{$firstName}.{$lastName}", '.').'@example.com',
                ]);
            }
        }
    }

    private function orderAmounts(int $price, int $discountPercent): array
    {
        $subtotal = (float) $price;
        $discountAmount = $subtotal > 0 ? round($subtotal * $discountPercent / 100, 2) : 0.0;
        $taxableAmount = max(0, $subtotal - $discountAmount);
        $taxAmount = round($taxableAmount * (float) config('billing.tax_rate'), 2);

        return [
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'tax_amount' => $taxAmount,
            'total' => round($taxableAmount + $taxAmount, 2),
        ];
    }
}
