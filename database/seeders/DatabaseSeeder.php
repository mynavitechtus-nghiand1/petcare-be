<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Learner',
            'email' => 'learner@example.com',
            'password' => 'password',
        ]);

        $this->call(PetCareSeeder::class);
    }
}
