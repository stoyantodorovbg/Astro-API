<?php

namespace Database\Factories;

use App\Models\Configuration;
use Illuminate\Database\Eloquent\Factories\Factory;

class ConfigurationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Configuration::class;

    /**
     * Define the model's default state.
     *
     * @return array
     * @throws \JsonException
     */
    public function definition()
    {
        return [
            'name'        => $this->faker->unique()->word(),
            'description' => $this->faker->text,
            'command'     => $this->faker->words(5, true),
            'options'     => json_encode([
                $this->faker->word => $this->faker->word
            ], JSON_THROW_ON_ERROR),
        ];
    }
}
