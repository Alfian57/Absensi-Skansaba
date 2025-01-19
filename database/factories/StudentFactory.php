<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Student>
 */
class StudentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nisn' => $this->faker->unique->numerify('##########'),
            'nis' => $this->faker->unique->numerify('#####'),
            'name' => $this->faker->name(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi',
            'date_of_birth' => $this->faker->date(),
            'gender' => mt_rand(0, 1),
            'address' => $this->faker->address(),
            'grade_id' => mt_rand(1, 48),
            'entry_year' => '2022',
        ];
    }
}
