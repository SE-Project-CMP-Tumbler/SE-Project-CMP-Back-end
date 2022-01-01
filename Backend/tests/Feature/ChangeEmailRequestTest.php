<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Http\Misc\Helpers\Errors;
use App\Http\Misc\Helpers\Config;
use App\Models\User;

class ChangeEmailRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  test email is required
     *
     * @return void
     */

    public function testRequiredEmail()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "password" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('PUT', 'api/change_email', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "email" =>  User::factory()->make()->email
        ];
        $response = $this
        ->json('PUT', 'api/change_email', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_PASSWORD,
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
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "email" => "test.com",
            "password" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('PUT', 'api/change_email', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
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
        $user1 = User::factory()->create();

        $user2 = User::factory()->create();
        $token = $user2->createToken('Auth Token')->accessToken;
        $request = [
            "email" => $user1->email,
            "password" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('PUT', 'api/change_email', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::EMAIL_TAKEN,
            ]
        ]);
    }
    /**
     *  test invalid password
     *
     * @return void
     */

    public function testIvalidPassword()
    {
        $user = User::factory()->create();
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "email" => User::factory()->make()->email,
            "password" => "Mm123sdsd455"
        ];
        $response = $this
        ->json('PUT', 'api/change_email', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::INVALID_CHANGE_PASSWORD,
            ]
        ]);
    }
}