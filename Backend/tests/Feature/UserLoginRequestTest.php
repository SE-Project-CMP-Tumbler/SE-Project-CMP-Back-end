<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserLoginRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     *  test email is required
     *
     * @return void
     */

    public function testRequiredEmail()
    {

        $email = 'testemail1@testloginrequest.com';
        $password = 'testpassword';
        $user = User::factory()->create(['email' => $email,'password' => Hash::make($password)]);
        $request = [
            "password" => $password
        ];
        $response = $this
        ->json('POST', 'api/login', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_EMAIL,
            ]
        ]);
        $user->delete();
    }
    /**
     *  test password is required
     *
     * @return void
     */

    public function testRequiredPassword()
    {

        $email = 'testemail1@testloginrequest.com';
        $password = 'testpassword';
        $user = User::factory()->create(['email' => $email,'password' => Hash::make($password)]);
        $request = [
            "email" => $email
        ];
        $response = $this
        ->json('POST', 'api/login', $request, Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_PASSWORD,
            ]
        ]);
        $user->delete();
    }
}
