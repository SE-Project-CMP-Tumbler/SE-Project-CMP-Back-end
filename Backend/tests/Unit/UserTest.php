<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /**
     *register Without Google But Sending a GoogleId
     *
     * @return void
     */
    public function testUnexpectedGoogleIdResgisteration()
    {
        $userService = new UserService();
        $user = $userService->register(
            'user2023@gmail.com',
            '54545454DFDFff', // password
            22,
            false,
            'CairoBlogs',
            '1234567891234567893'
        );
        $this->assertNull($user);
    }
    /**
     * register With Google But Not Sending GoogleId
     *
     * @return void
     */
    public function testMissingGoogleIdRegisteration()
    {
        $userService = new UserService();
        $user = $userService->register(
            'user2023@gmail.com',
            '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            22,
            true,
            'CairoBlogs'
        );
        $this->assertNull($user);
    }
    /**
     * grant access token for a given user
     *
     * @return void
     */
    public function testGrantAccessToken()
    {
        $userService = new UserService();
        $user = User::factory()->create();
        $granted = $userService->grantAccessToken($user);
        $user->delete();
        $this->assertTrue($granted);
    }
    /**
     * test Successs Login
     *
     * @return void
     */
    public function testSuccesssLogin()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email' => 'testemail1@test.com','password' => Hash::make('testpassword')]);
        $success = $userService->checkLoginCredentials('testemail1@test.com', 'testpassword');
        $user->delete();
        $this->assertNotNull($success);
    }
    /**
     * test failed Login
     *
     * @return void
     */
    public function testFailedLogin()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email' => 'testemail2@test.com','password' => Hash::make('testpassword')]);
        $fail = $userService->checkLoginCredentials('testemail2@test.com', 'wrongPassword');
        $user->delete();
        $this->assertNull($fail);
    }
    /**
     * resending verification mail
     *
     * @return void
     */
    public function testResendingMail()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email_verified_at' => null]);
        $success = $userService->verifyUserEmail($user, 1);

        $user->delete();
        $this->assertTrue($success);
    }
    /**
     * Verifing mail
     *
     * @return void
     */
    public function testVerifingMail()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email_verified_at' => null]);
        $success = $userService->verifyUserEmail($user, 0);

        $user->delete();
        $this->assertTrue($success);
    }
     /**
     * trying to resend/verify the mail of a Verified mail
     *
     * @return void
     */
    public function testVerifiedUserTryingToResendMailOrVerfiy()
    {
        $userService = new UserService();
        $user = User::factory()->create();
        $resend = $userService->verifyUserEmail($user, 1);
        $verify = $userService->verifyUserEmail($user, 0);


        $user->delete();
        $this->assertFalse(($resend || $verify));
    }
     /**
     * Matching the Magic Link Hash and id
     *
     * @return void
     */
    public function testMatchMagicLinkHash()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email' =>  'testemail3@test.com']);
        $Match = $userService->matchMagicLinkHash($user->id, sha1('testemail3@test.com'));
        $user->delete();
        $this->assertTrue($Match);
    }
     /**
     * trying to Match a fake Magic Link Hash and id
     *
     * @return void
     */
    public function testFakeMagicLinkHash()
    {
        $userService = new UserService();
        $user = User::factory()->create(['email' =>  'testemail3@test.com']);
        $Match = $userService->matchMagicLinkHash(215, sha1('kgnbjkngjn@test.com'));
        $user->delete();
        $this->assertFalse($Match);
    }
}
