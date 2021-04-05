<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contract::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'company_id' => Company::factory(),
            'start_date' => Carbon::today()->subDays(rand(0, 365)),
            'end_date' => function (array $attributes) {
                return $attributes['start_date']->addDays(rand(0, 365));
            },
            'quota' => $this->faker->numberBetween(1, 20),
            'terms_and_conditions' => $this->faker->sentence
        ];
    }
}
