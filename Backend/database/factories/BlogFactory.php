<?php

namespace Database\Factories;

use App\Models\User;
use App\Http\Misc\Helpers\Config;
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
            "username" => ($this->faker->unique()->firstName) . ($this->faker->unique()->firstName),
            "description" => $this->faker->text(),
            "title" => $this->faker->title(),
            "user_id" => $user->id,
            "header_image" =>  Config::DEFAULT_AVATAR ,
            "avatar" =>  Config::DEFAULT_HEADER_IMAGE,
            "avatar_shape" => "square"

        ];
    }
}
