<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserGoogleRegisterRequestTest extends TestCase
{
    use WithFaker;

    /**
     *  test google access token is required
     *
     * @return void
     */

    public function testRequiredGoogleAccessToken()
    {
        $request = [
            "blog_username" => $this->faker->firstName(),
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The google access token field is required.",
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
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_BLOGNAME,
            ]
        ]);
    }
    /**
     *  test age is required
     *
     * @return void
     */

    public function testRequiredAge()
    {
        $request = [
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "blog_username" => $this->faker->firstName(),
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_AGE,
            ]
        ]);
    }
    /**
     * test ivalid google access token
     *
     * @return void
     */

    public function testIvalidGoogleAccessToken()
    {
        $request = [
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "blog_username" => $this->faker->firstName(),
            "age" => 22
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "404",
                "msg" =>  "not found",
            ]
        ]);
    }
    public function testInvalidAge()
    {
        $request = [
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "blog_username" => $this->faker->firstName(),
            "age" => 500
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::INVALID_AGE,
            ]
        ]);
    }
    /**
     *  test illegal age (under 13)
     *
     * @return void
     */

    public function testIllegalAge()
    {
        $request = [
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "blog_username" => $this->faker->firstName(),
            "age" => 12
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MIN_AGE,
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
            "google_access_token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{60}') ,
            "blog_username" => $this->faker->name(),
            "age" => 12
        ];
        $response = $this
        ->json('POST', 'api/register_with_google', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::EMAIL_INVALID_FORMAT,
            ]
        ]);
    }
}
