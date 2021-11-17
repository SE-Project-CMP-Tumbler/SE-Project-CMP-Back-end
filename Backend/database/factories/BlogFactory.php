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
        $users = User::all();
        $available_user_ids = $users->map(function ($user) {
            return $user->id;
        })->toArray();

        return [
            "username" => $this->faker->unique()->name,
            "avatar" => $this->faker->name,
            //"avatar_shape" => $this->faker->randomElement(['circle','square']),
            "title" => $this->faker->title(),
            "user_id" => $this->faker->randomElement($available_user_ids)
        ];
    }
}