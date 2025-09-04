<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Non-aktifkan foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Hapus data existing jika ada
        DB::table('users')->truncate();
        
        // Aktifkan kembali foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Data admin utama
        User::create([
            'name' => 'Administrator Utama',
            'email' => 'admin@pos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data kasir
        $cashiers = [
            [
                'name' => 'Kasir 1',
                'email' => 'kasir1@pos.com',
                'password' => Hash::make('kasir123'),
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'name' => 'Kasir 2', 
                'email' => 'kasir2@pos.com',
                'password' => Hash::make('kasir123'),
                'role' => 'cashier',
                'is_active' => true,
            ],
            [
                'name' => 'Kasir 3',
                'email' => 'kasir3@pos.com',
                'password' => Hash::make('kasir123'),
                'role' => 'cashier',
                'is_active' => true,
            ]
        ];

        foreach ($cashiers as $cashier) {
            User::create(array_merge($cashier, [
                'email_verified_at' => now(),
                'remember_token' => Str::random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Data manager
        User::create([
            'name' => 'Manager Utama',
            'email' => 'manager@pos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('manager123'),
            'role' => 'manager',
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data admin tambahan
        User::create([
            'name' => 'Admin Support',
            'email' => 'admin2@pos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Data user non-aktif (contoh)
        User::create([
            'name' => 'Kasir Non-Aktif',
            'email' => 'kasir.inactive@pos.com',
            'email_verified_at' => now(),
            'password' => Hash::make('kasir123'),
            'role' => 'cashier',
            'is_active' => false, // Non-aktif
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('User seeder berhasil dijalankan!');
        $this->command->info('Admin: admin@pos.com / password123');
        $this->command->info('Kasir: kasir1@pos.com / kasir123');
        $this->command->info('Manager: manager@pos.com / manager123');
    }
}