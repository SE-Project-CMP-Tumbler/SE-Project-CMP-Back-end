<?php

namespace Database\Factories;

use App\Models\Blog;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Database\Eloquent\Factories\Factory;

class ThemeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $user = User::factory()->create();
        $blog = Blog::factory()->create(['user_id' => $user->id]);
        return [
            'blog_id' => $blog->id,
            "color_title" => "#000000",
            "font_title" => "Gibson",
            "font_weight_title" => "bold",
            "background_color" => "#FFFFFF",
            "accent_color" => "#e17e66",
            "body_font" => "Helvetica Neue"
        ];
    }
}
