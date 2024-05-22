<?php

// database/factories/EmployeeFactory.php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        return [
            'user_id' => User::factory()->create()->id,
            'nickname' => $this->faker->firstName,
            'phone_number' => $this->faker->phoneNumber,
            'total_score' => $this->faker->numberBetween(0, 1000),
            // Add more attributes as needed
        ];
    }
}
