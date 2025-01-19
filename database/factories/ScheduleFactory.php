<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Schedule>
 */
class ScheduleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'training_year' => '2022/2023',
            'class_year' => 'ganjil',
            'teacher_id' => rand(1, 100),
            'subject_id' => rand(1, 100),
            'grade_id' => rand(1, 100),
            'day' => 'senin',
            'time_start' => '15:55:00',
            'time_finish' => '15:55:00',
        ];
    }
}
