<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LaborantinSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Laborantin Principal',
            'email' => 'laborantin@example.com',
            'password' => Hash::make('P@55word'),
            'role' => 'laborantin',
        ]);
    }
}
