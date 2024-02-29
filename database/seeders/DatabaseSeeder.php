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
            'name' => 'Anatel LTDA',
            'cnpj' => '38886492000137'
        ]);

        $company2 = \App\Models\Company::factory()->create([
            'name' => 'Smart Fitness',
            'cnpj' => '65700151000106'
        ]);

         \App\Models\User::factory()->create([
             'name' => 'apple',
             'email' => 'apple@onhappy.com',
             'password' => bcrypt('1234'),
             'role' => 'employee',
             'company_id' => $company->id
         ]);

        \App\Models\User::factory()->create([
            'name' => 'Andreew',
            'email' => 'Andreew@onhappy.com',
            'password' => bcrypt('1234'),
            'role' => 'master',
        ]);

        \App\Models\User::factory()->create([
            'name' => 'xiaomi',
            'email' => 'xiaomi@onhappy.com',
            'password' => bcrypt('1234'),
            'role' => 'employee',
            'company_id' => $company->id
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Anatel',
            'email' => 'anatel@onhappy.com',
            'password' => bcrypt('1234'),
            'role' => 'admin',
            'company_id' => $company->id
        ]);

        \App\Models\User::factory()->create([
            'name' => 'joao Personal',
            'email' => 'joao@smart.com',
            'password' => bcrypt('1234'),
            'role' => 'employee',
            'company_id' => $company2->id
        ]);

        \App\Models\User::factory()->create([
            'name' => 'Bia Gerente',
            'email' => 'bia@smart.com',
            'password' => bcrypt('1234'),
            'role' => 'admin',
            'company_id' => $company2->id
        ]);
    }
}
