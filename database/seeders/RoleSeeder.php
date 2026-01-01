<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Usamos insertOrIgnore para no duplicar si corres el seeder 2 veces
        DB::table('roles')->insertOrIgnore([
            ['id' => 1, 'name' => 'owner', 'label' => 'Dueño', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'moderator', 'label' => 'Moderador', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
