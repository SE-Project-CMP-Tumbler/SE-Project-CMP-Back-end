<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BlogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();

        return [
            "username" => $this->faker->unique()->name,
            "avatar" => $this->faker->name,
            "description" => $this->faker->text(),
            "title" => $this->faker->title(),
            "user_id" => $user->id

        ];
    }
}
