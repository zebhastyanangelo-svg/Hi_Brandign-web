<?php

namespace Database\Seeders;

use App\Models\AdminUser;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = 'gerencia@hibranding.net';

        $admin = AdminUser::query()->where('email', $email)->first()
            ?? AdminUser::query()->orderBy('id')->first()
            ?? new AdminUser;

        $admin->forceFill([
            'name' => 'Administrador Hi',
            'email' => $email,
            'password' => '..Hibrandig..00',
        ])->save();
    }
}
