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
        User::factory()->create([
            'name' => 'Alejandro Martinez',
            'email' => 'alejandro@test.com',
            'password' => bcrypt('alejandro'),
        ]);
    }
}
