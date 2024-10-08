<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\VehicleType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'username' => 'admin',
            'full_name' => 'admintest',
            'email' => 'admin@admin.com',
            'password' => 'admin123',
            'role' => 'admin'
        ]);
        
        User::factory()->create([
            'username' => 'staff',
            'full_name' => 'stafftest',
            'email' => 'staff@staff.com',
            'password' => 'staff123',
            'role' => 'staff'
        ]);
        
        User::factory()->create([
            'username' => 'pengunjung',
            'full_name' => 'pengunjungtest',
            'email' => 'pengunjung@pengunjung.com',
            'password' => 'pengunjung123',
            'role' => 'pengunjung'
        ]);

        VehicleType::create([
            'name' => 'Motor',
        ]);
        
        VehicleType::create([
            'name' => 'Mobil',
        ]);

        // User::factory()->create([
        //     'username' => 'test',
        //     'email' => 'test@staff.com',
        //     'password' => 'staff123',
        //     'role_id' => '2'
        // ]);
    }
}
