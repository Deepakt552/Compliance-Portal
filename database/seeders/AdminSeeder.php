<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name'     => 'Admin',
            'email'    => 'admin@navkarservices.com',
            'password' => 'admin@123',
        ]);

        $this->command->info('Admin user seeded successfully in admins table.');
    }
}