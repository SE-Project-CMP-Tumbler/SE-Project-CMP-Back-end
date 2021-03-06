<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserCheckRegisterCredentialsTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /**
     *  test email is required
     *
     * @return void
     */

    public function testRequiredEmail()
    {
        $request = [
            "blog_username" => $this->faker->firstName(),
            "password" => "Mm123sdsd455",
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_EMAIL,
            ]
        ]);
    }
    /**
     *  test password is required
     *
     * @return void
     */

    public function testRequiredPassword()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "blog_username" => $this->faker->firstName(),
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_PASSWORD,
            ]
        ]);
    }
    /**
     *  test blog_username is required
     *
     * @return void
     */

    public function testRequiredBlogUsername()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "password" => "Mm123sdsd455",
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_BLOGNAME,
            ]
        ]);
    }
    /**
     *  test invalid email
     *
     * @return void
     */

    public function testIvalidEmail()
    {
        $request = [
            "email" => "test.com",
            "blog_username" => $this->faker->firstName(),
            "password" => "Mm123sdsd455",
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::NOT_VALID_EMAIL,
            ]
        ]);
    }
    /**
     *  test repeated email
     *
     * @return void
     */

    public function testRepeatedEmail()
    {
        $email = $this->faker->safeEmail();
        $user = User::factory()->create(['email' => $email]);
        $request = [
            "email" => $email,
            "blog_username" => $this->faker->firstName(),
            "password" => "Mm123sdsd455",
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::EMAIL_TAKEN,
            ]
        ]);
    }
    /**
     *  test wrong username format
     *
     * @return void
     */

    public function testWrongFormatUsername()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "blog_username" => $this->faker->name(),
            "password" => "Mm123sdsd455",
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::EMAIL_INVALID_FORMAT,
            ]
        ]);
    }
    /**
     *  test short password
     *
     * @return void
     */

    public function testShortPassword()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "blog_username" => $this->faker->firstName(),
            "password" => "123",
            "age" => 14
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::SHORT_PASSWORD,
            ]
        ]);
    }
    /**
     *  test entering a password without any numbers
     *
     * @return void
     */

    public function testPasswordWithoutNumbers()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "blog_username" => $this->faker->firstName(),
            "password" => "kmkKMKMKCSNJN",
            "age" => 14
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must contain at least one number.",
            ]
        ]);
    }
    /**
     *  test entering a password without mixed case letters
     *
     * @return void
     */

    public function testPasswordWithoutMixedcaseLetters()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "blog_username" => $this->faker->firstName(),
            "password" => "asasasas12121",
            "age" => 14
        ];
        $response = $this
        ->json('POST', 'api/check_register_credentials', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must contain at least one uppercase and one lowercase letter.",
            ]
        ]);
    }
}
