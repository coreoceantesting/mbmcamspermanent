<?php

namespace Database\Factories;

use App\Models\Clas;
use App\Models\Designation;
use App\Models\Shift;
use App\Models\Ward;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shift_id' => Shift::inRandomOrder()->first()->id,
            'ward_id' => Ward::inRandomOrder()->first()->id,
            'clas_id' => Clas::inRandomOrder()->first()->id,
            'designation_id' => Designation::inRandomOrder()->first()->id,
            'doj' => fake()->date(),
            'is_ot' => fake()->randomElement( array('y', 'n') ),
            'is_divyang' => fake()->randomElement( array('y', 'n') ),
            'permanent_address' => fake()->sentence(),
            'present_address' => fake()->sentence(),
        ];
    }
}
