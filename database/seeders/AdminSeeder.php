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
        Admin::updateOrCreate(
            ['email' => 'itdev@navkarservices.com'],
            [
                'name'     => 'deepak tiwari',
                'password' => Hash::make('12345678'),
            ]
        );

        $this->command->info('Admin user deepak tiwari seeded successfully in admins table.');
    }
}