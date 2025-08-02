<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ControleurSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Controleur Principal',
            'email' => 'controleur@example.com',
            'password' => Hash::make('P@55word'),
            'role' => 'controleur',
        ]);
    }
}
