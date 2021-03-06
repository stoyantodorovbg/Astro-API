<?php

namespace Database\Factories;

use App\Models\Data;
use Illuminate\Database\Eloquent\Factories\Factory;

class DataFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Data::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \JsonException
     */
    public function definition()
    {
        return [
            'key'  => $this->faker->sentence,
            'data' => json_encode([
                $this->faker->word => $this->faker->word
            ], JSON_THROW_ON_ERROR),
        ];
    }
}
