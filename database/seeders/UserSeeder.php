<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name'     => 'Administrador',
            'email'    => 'admin@email.com',
            'password' => bcrypt('password'),
        ]);

        User::create([
            'name'     => 'Demo',
            'email'    => 'demo@email.com',
            'password' => bcrypt('password'),
        ]);
    }
}
