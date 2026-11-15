<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = ['name', 'slug', 'description', 'price', 'billing_period', 'capacity', 'featured', 'features', 'sort_order'];

    protected function casts(): array
    {
        return [
            'price' => 'integer',
            'capacity' => 'integer',
            'featured' => 'boolean',
            'features' => 'array',
        ];
    }

    /**
     * Catálogo base de membresías según el brochure oficial.
     *
     * @return array<int, array{name: string, slug: string, description: string, price: int, billing_period: string, capacity: int|null, featured: bool, features: array<int, string>, sort_order: int}>
     */
    public static function defaultCatalog(): array
    {
        return [
            [
                'name' => 'Soft Landing (Tu Puente a tu Propósito)',
                'slug' => 'soft-landing',
                'description' => 'Acompañamiento empresarial y profesional para marcas que buscan oportunidad en el mercado venezolano e internacional.',
                'price' => 1800,
                'billing_period' => 'mes',
                'capacity' => null,
                'featured' => false,
                'features' => [
                    'Membresía de salas multifuncional.',
                    'Asesoría en Estrategias de Branding corporativo, employer y territorial.',
                    'Servicios logísticos ejecutivos, traslados y protocolo corporativo.',
                    'Networking Institucional y conexión directa con cámaras de comercio y aliados estratégicos.',
                    'Estrategia de Adaptación Cultural y modelo de negocio.',
                    'Soporte tecnológico (desarrollo web, ciberseguridad, agentes IA y Apps).',
                    'Catálogo de selección y bienestar de talento humano.',
                ],
                'sort_order' => 1,
            ],
            [
                'name' => 'Hi Sala (Eventos y Reuniones Corporativas)',
                'slug' => 'hi-sala',
                'description' => 'Espacio profesional de alta gama con capacidad para 25 a 30 personas.',
                'price' => 4200,
                'billing_period' => 'mes',
                'capacity' => null,
                'featured' => true,
                'features' => [
                    '16 horas mensuales fraccionables (Modelo de Consumo Flexible).',
                    'Conectividad Wi-Fi de alta velocidad y asistencia técnica.',
                    'Smart TV de 60 pulgadas para presentaciones.',
                    'Hidratación ilimitada (agua, café de especialidad y té).',
                    'Control de acceso automatizado con código personalizado y seguridad privada.',
                ],
                'sort_order' => 2,
            ],
            [
                'name' => 'Hi Plus / Experience (Productos & Soluciones Adicionales)',
                'slug' => 'hi-plus-experience',
                'description' => 'Servicios paquetizados para elevar el nivel de tu marca y eventos corporativos.',
                'price' => 8900,
                'billing_period' => 'mes',
                'capacity' => null,
                'featured' => false,
                'features' => [
                    'Producción audiovisual (streaming, grabación y fotografía profesional).',
                    'Branding personalizado del evento en pantallas, señalética y Material POP.',
                    'Catering ejecutivo y estaciones de snacks personalizados.',
                    'Creación de Landing Pages y automatización con IA.',
                    'Asesoría y diseño de experiencias para eventos.',
                ],
                'sort_order' => 3,
            ],
        ];
    }
}
