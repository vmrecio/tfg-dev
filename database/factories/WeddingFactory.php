<?php

namespace Database\Factories;

use App\Models\Wedding;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<\App\Models\Wedding>
 */
class WeddingFactory extends Factory
{
    protected $model = Wedding::class;

    public function definition(): array
    {
        $name = 'Boda de ' . fake()->firstName() . ' y ' . fake()->firstName();
        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(6),
            'event_date' => fake()->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
            'location' => fake()->city(),
            'description' => fake()->sentence(),
        ];
    }
}

