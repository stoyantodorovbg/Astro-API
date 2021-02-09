<?php

namespace Database\Factories;

use App\Models\HeliacalEventType;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeliacalEventTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HeliacalEventType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => 'heliacal rising'
        ];
    }
}
