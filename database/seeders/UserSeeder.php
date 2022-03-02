<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

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

        User::factory()->create([
            'name' => 'Alejandro Martinez',
            'email' => 'alejandro@test.com',
            'password' => bcrypt('alejandro'),
        ])->assignRole('admin');

        User::factory(50)->create();
    }
}
