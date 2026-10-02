<?php

namespace Database\Seeders;

use App\Models\CommunityMember;
use App\Models\Event;
use App\Models\Membership;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        foreach ([
            ['name' => 'Daylight', 'slug' => 'daylight', 'description' => 'Un lugar para empezar a encontrarte.', 'price' => 1800, 'billing_period' => 'mes', 'featured' => false, 'features' => ['Acceso flexible entre semana', 'Café de especialidad', 'Comunidad Hi'], 'sort_order' => 1],
            ['name' => 'Studio', 'slug' => 'studio', 'description' => 'Tu base para hacer crecer lo que sigue.', 'price' => 4200, 'billing_period' => 'mes', 'featured' => true, 'features' => ['Escritorio dedicado', 'Salas de juntas · 8 h', 'Acceso a eventos privados'], 'sort_order' => 2],
            ['name' => 'House', 'slug' => 'house', 'description' => 'Un espacio propio para equipos ambiciosos.', 'price' => 8900, 'billing_period' => 'mes', 'featured' => false, 'features' => ['Oficina privada para 2', 'Salas de juntas · 20 h', 'Acompañamiento estratégico'], 'sort_order' => 3],
        ] as $membership) {
            Membership::updateOrCreate(['slug' => $membership['slug']], $membership);
        }

        foreach ([
            ['name' => 'Lucía Herrera', 'slug' => 'lucia-herrera', 'company' => 'Norte Studio', 'role' => 'Founder & Creative Director', 'category' => 'Consumer & Creative', 'initials' => 'LH', 'tone' => 'clay', 'email' => 'lucia@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=700&q=82'],
            ['name' => 'Mateo Ríos', 'slug' => 'mateo-rios', 'company' => 'Forma Capital', 'role' => 'Managing Partner', 'category' => 'Fintech & VC', 'initials' => 'MR', 'tone' => 'olive', 'email' => 'mateo@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=700&q=82'],
            ['name' => 'Camila Duarte', 'slug' => 'camila-duarte', 'company' => 'Nativa Labs', 'role' => 'Co-founder', 'category' => 'AI & Deeptech', 'initials' => 'CD', 'tone' => 'blue', 'email' => 'camila@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1531123897727-8f129e1688ce?auto=format&fit=crop&w=700&q=82'],
            ['name' => 'Andrés Soler', 'slug' => 'andres-soler', 'company' => 'Casa Nómada', 'role' => 'Founder', 'category' => 'Consumer & Creative', 'initials' => 'AS', 'tone' => 'rose', 'email' => 'andres@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=700&q=82'],
            ['name' => 'Valentina Cruz', 'slug' => 'valentina-cruz', 'company' => 'Lumen Health', 'role' => 'CEO', 'category' => 'AI & Deeptech', 'initials' => 'VC', 'tone' => 'olive', 'email' => 'valentina@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=700&q=82'],
            ['name' => 'Diego Montes', 'slug' => 'diego-montes', 'company' => 'Marea Ventures', 'role' => 'Investor', 'category' => 'Fintech & VC', 'initials' => 'DM', 'tone' => 'clay', 'email' => 'diego@example.com', 'photo_url' => 'https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&w=700&q=82'],
        ] as $member) {
            CommunityMember::updateOrCreate(['slug' => $member['slug']], $member);
        }

        foreach ([
            ['title' => 'Founder Dinner & Demo Night', 'category' => 'CENA · NETWORKING', 'description' => 'Una mesa íntima para compartir lo que estamos construyendo y conocer a quienes vienen a cambiar las reglas.', 'place' => 'Casa Hi, Polanco', 'starts_at' => now()->addDays(7)->setTime(19, 0), 'is_featured' => true, 'is_published' => true],
            ['title' => 'Construir una marca que perdure', 'category' => 'WORKSHOP · BRAND', 'description' => 'Una sesión práctica sobre estrategia, voz y decisiones de diseño con intención.', 'place' => 'Estudio Norte', 'starts_at' => now()->addDays(14)->setTime(10, 30), 'is_featured' => false, 'is_published' => true],
            ['title' => 'Capital con propósito', 'category' => 'CONVERSACIÓN · CAPITAL', 'description' => 'Fundadores e inversionistas conversan sobre crecimiento sostenible y nuevas formas de financiarlo.', 'place' => 'Casa Hi, Polanco', 'starts_at' => now()->addDays(21)->setTime(18, 0), 'is_featured' => false, 'is_published' => true],
            ['title' => 'Product office hours', 'category' => 'CLÍNICA · PRODUCTO', 'description' => 'Trae tu reto de producto y trabajémoslo con líderes que ya recorrieron ese camino.', 'place' => 'Sala Estudio', 'starts_at' => now()->addDays(28)->setTime(12, 0), 'is_featured' => false, 'is_published' => true],
        ] as $event) {
            Event::updateOrCreate(['title' => $event['title']], $event);
        }
    }
}
