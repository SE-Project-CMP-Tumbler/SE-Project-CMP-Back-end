<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ForgotPasswordRequestTest extends TestCase
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
        ];
        $response = $this
        ->json('POST', 'api/forgot_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_EMAIL,
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
            "email" => "test.com"
        ];
        $response = $this
        ->json('POST', 'api/forgot_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::NOT_VALID_EMAIL,
            ]
        ]);
    }
    /**
     *  test not found email
     *
     * @return void
     */

    public function testNotFoundEmail()
    {
        $request = [
            "email" => $this->faker->safeEmail()
        ];
        $response = $this
        ->json('POST', 'api/forgot_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "404",
                "msg" => "not found",
            ]
        ]);
    }
    /**
     *  test success forgot password test
     *
     * @return void
     */

    public function testForgotPasswordTest()
    {
        $user = User::factory()->create();
        $request = [
            "email" => $user->email
        ];
        $response = $this
        ->json('POST', 'api/forgot_password', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "Successful response",
            ]
        ]);
    }
}
