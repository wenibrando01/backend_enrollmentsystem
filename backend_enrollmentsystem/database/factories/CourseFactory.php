<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition()
    {
        return [
            'course_code' => strtoupper($this->faker->unique()->bothify('CSE###')),
            'course_name' => $this->faker->words(3, true),
            'capacity' => $this->faker->numberBetween(1, 10),
        ];
    }
}
