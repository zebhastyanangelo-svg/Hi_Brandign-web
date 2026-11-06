<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            [
                'name' => 'Pago Móvil',
                'type' => PaymentMethod::TYPE_PAGO_MOVIL,
                'bank' => 'Banco de Venezuela',
                'tax_id' => 'J-40123456-7',
                'account_number' => '0412-123-45-67',
                'account_holder' => 'Hi Branding C.A.',
                'notes' => 'Disponible 24/7. Envía el comprobante con la referencia SMS.',
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Transferencia Banesco',
                'type' => PaymentMethod::TYPE_TRANSFERENCIA,
                'bank' => 'Banesco',
                'tax_id' => 'J-40123456-7',
                'account_number' => '0134-0000-00-00000000',
                'account_holder' => 'Hi Branding C.A.',
                'notes' => 'Transferencias nacionales. Verifica el titular antes de enviar.',
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Transferencia Mercantil',
                'type' => PaymentMethod::TYPE_TRANSFERENCIA,
                'bank' => 'Mercantil',
                'tax_id' => 'J-40123456-7',
                'account_number' => '0105-0000-00-00000000',
                'account_holder' => 'Hi Branding C.A.',
                'notes' => 'Transferencias nacionales desde cualquier banco.',
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'name' => 'Binance Pay',
                'type' => PaymentMethod::TYPE_BINANCE,
                'bank' => null,
                'tax_id' => null,
                'account_number' => '348927155',
                'account_holder' => 'HiBranding',
                'notes' => 'Envíos en USDT (red BEP20). Indica tu ID de Binance Pay.',
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Zelle',
                'type' => PaymentMethod::TYPE_ZELLE,
                'bank' => null,
                'tax_id' => null,
                'account_number' => 'pagos@hibranding.com',
                'account_holder' => 'Hi Branding LLC',
                'notes' => 'Pagos desde cuentas bancarias de Estados Unidos.',
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($methods as $method) {
            PaymentMethod::query()->firstOrCreate(
                ['name' => $method['name'], 'type' => $method['type']],
                $method
            );
        }
    }
}
