<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        AdminUser::firstOrCreate(
            ['email' => 'admin@hibranding.com'],
            [
                'name' => 'Administrador Hi',
                'password' => bcrypt('admin123'),
            ]
        );
    }
}