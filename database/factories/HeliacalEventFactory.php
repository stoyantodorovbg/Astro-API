<?php

namespace Database\Factories;

use App\Models\HeliacalEvent;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class HeliacalEventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = HeliacalEvent::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'expected_at' => Carbon::now()->addDay()->toDateTimeString(),
            'visible_for' => $this->faker->randomFloat(2, 1, 15) . ' min',
        ];
    }
}
