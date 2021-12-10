<?php

namespace Tests\Feature;

use App\Http\Misc\Helpers\Errors;
use App\Models\User;
use App\Http\Misc\Helpers\Config;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class ChangePasswordRequestTest extends TestCase
{
    use WithFaker;

    /**
     *  test Current Password is required
     *
     * @return void
     */

    public function testRequiredCurrentPassword()
    {
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "password" => "cvcvV1dvdvv",
            "password_confirmation" => "cvcvV1dvdvv"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_CHANGE_PASSWORD,
            ]
        ]);
    }
    /**
     *  test Password Confirmation is required
     *
     * @return void
     */

    public function testRequiredPasswordConfirmation()
    {
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
            "password" => "cvcvV1dvdvv"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::INVALID_CHANGE_PASSWORD_CONFORMATION,
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
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => Errors::MISSING_CHANGE_PASSWORD,
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
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
            "password" => "123",
            "password_confirmation" => "123"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
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
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
            "password" => "dfdfdfASSASSA",
            "password_confirmation" => "dfdfdfASSASSA"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
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
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
            "password" => "jhfxbghbfghbjhfb21454545",
            "password_confirmation" => "jhfxbghbfghbjhfb21454545"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "422",
                "msg" => "The password must contain at least one uppercase and one lowercase letter.",
            ]
        ]);
    }
    /**
     *  test change password
     *
     * @return void
     */

    public function testChangePassword()
    {
        $password = "kfbjkfn321321SS";
        $user = User::factory()->create(["password" => Hash::make($password)]);
        $token = $user->createToken('Auth Token')->accessToken;
        $request = [
            "current_password" => $password,
            "password" => "kmkmkmKMKMK2131231",
            "password_confirmation" => "kmkmkmKMKMK2131231"
        ];
        $response = $this
        ->json('PUT', 'api/change_password', $request, ['Authorization' => 'Bearer ' . $token], Config::JSON)
        ->assertJson([
            "meta" => [
                "status" => "200",
                "msg" => "Successful response",
            ]
        ]);
    }
}
