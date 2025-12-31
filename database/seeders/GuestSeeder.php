<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GuestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $familyCode = '1234-DF';

        DB::table('guests')->insert([
            [
                'invitation_code' => $familyCode,
                'name' => 'Damián',
                'attendance' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'invitation_code' => $familyCode,
                'name' => 'Flor',
                'attendance' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        DB::table('guests')->insert([
            'invitation_code' => '1234-ALBERTO',
            'name' => 'Tío Alberto',
            'attendance' => 'pending',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
