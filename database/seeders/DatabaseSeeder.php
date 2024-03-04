<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $company =\App\Models\Company::factory()->create([
            'name' => 'Aranha verso',
            'cnpj' => '38886492000137'
        ]);

        $company2 = \App\Models\Company::factory()->create([
            'name' => 'Marvel',
            'cnpj' => '65700151000106'
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Andreew',
            'email' => 'Andreew@onhappy.com',
            'password' => bcrypt('1234'),
            'role' => 'master',
        ]);
    }
}
