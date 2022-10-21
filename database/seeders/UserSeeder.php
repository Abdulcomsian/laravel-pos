<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
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


        Role::create(['name' => 'admin']);
        Role::create(['name' => 'cachier']);

        User::updateOrCreate([
            'email' => 'admin@gmail.com'
        ], [
            'first_name' => 'Admin',
            'last_name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'role_id' => 1,
            'role_name' => 'admin',
        ]);

        User::updateOrCreate([
            'email' => 'cachier@gmail.com'
        ], [
            'first_name' => 'Cachier',
            'last_name' => 'cachier',
            'email' => 'cachier@gmail.com',
            'password' => bcrypt('cachier123'),
            'role_id' => 2,
            'role_name' => 'cachier',
        ]);
    }
}
