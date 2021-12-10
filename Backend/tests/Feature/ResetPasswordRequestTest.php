<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Models\Blog;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Password;

class ResetPasswordRequestTest extends TestCase
{
    use WithFaker;

    /**
     *  test token is required
     *
     * @return void
     */

    public function testRequiredToken()
    {
        $request = [
            "email" => $this->faker->safeEmail(),
            "password" => "Mm123sdsd455",
            "password_confirmation" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The token field is required.",
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
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => $this->faker->safeEmail(),
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_PASSWORD,
            ]
        ]);
    }
    /**
     *  test password confirmation is required
     *
     * @return void
     */

    public function testRequiredPasswordConfirmation()
    {
        $request = [
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => $this->faker->safeEmail(),
            "password" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_PASSWORD_CONFORMATION,
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
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => "test.com",
            "password" => "Mm123sdsd455",
            "password_confirmation" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::NOT_VALID_EMAIL,
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
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => $this->faker->safeEmail(),
            "password" => "123",
            "password_confirmation" => "123"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
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
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => $this->faker->safeEmail(),
            "password" => "kmkKMKMKCSNJN",
            "password_confirmation" => "kmkKMKMKCSNJN"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
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
            "token" => $this->faker->unique()->regexify('[0-9][A-Z][a-z]{40}') ,
            "email" => $this->faker->safeEmail(),
            "password" => "asasasas12121",
            "password_confirmation" => "asasasas12121"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must contain at least one uppercase and one lowercase letter.",
            ]
        ]);
    }
    /**
     *  test resseting the password
     *
     * @return void
     */

    public function testResetPassword()
    {
        $user = User::factory()->create();
        Blog::factory()->create(["is_primary" => true , "user_id" => $user->id]);
        $token = Password::createToken($user);
        $request = [
            "token" => $token,
            "email" => $user->email,
            "password" => "Aa123456789",
            "password_confirmation" => "Aa123456789"
        ];
        $response = $this
        ->json('POST', 'api/reset_password', $request, Config::JSON);
        $response ->assertStatus(200);
    }
}
