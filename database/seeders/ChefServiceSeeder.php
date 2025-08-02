<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ChefServiceSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'name' => 'Chef de Service',
            'email' => 'chefservice@example.com',
            'password' => Hash::make('P@55word'),
            'role' => 'chef_service',
        ]);
    }
}
