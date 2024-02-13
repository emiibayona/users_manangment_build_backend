<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $password_default = "password";

        User::factory(10)->create();

        User::factory()->create([
            'name' => 'Juan Build',
            'email' => 'juan@build.com',
            'password' => $password_default
        ]);

        User::factory()->create([
            'name' => 'Eitan Build',
            'email' => 'eitan@build.com',
            'password' => $password_default
        ]);
    }
}
