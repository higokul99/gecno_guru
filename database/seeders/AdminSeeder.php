<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'gecnoguru2020@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('N0n33dofpa$$'),
                'user_type' => 'admin'
            ]
        );

        User::updateOrCreate(
            ['email' => 'vrindav752@gmail.com'],
            [
                'name' => 'Vrinda',
                'password' => Hash::make('Vrinda@123'),
                'user_type' => 'manager'
            ]
        );
    }
}
