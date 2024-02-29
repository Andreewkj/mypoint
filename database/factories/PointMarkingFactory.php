<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PointMarking>
 */
class PointMarkingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'clocking_at' => fake()->dateTimeBetween('today', '+2 days'),
            'modified' => false,
        ];
    }
}
