<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
{
    use RefreshDatabase;

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
     /**
     * Matching the reset password Link token and id
     *
     * @return void
     */
    public function testMatchResetPasswordLink()
    {
        $userService = new UserService();
        $email = 'testemail3@test.com';
        $user = User::factory()->create(['email' =>  $email]);
        $token = Password::createToken($user);
        $emailForReset = $userService->matchResetPasswordLink($user->id, $token);
        $user->delete();
        $this->assertTrue(( $emailForReset == $email));
    }
     /**
     * trying to Match a fake reset password Link token and id
     *
     * @return void
     */
    public function testFakeResetPasswordLink()
    {
        $userService = new UserService();
        $email = 'testemail3@test.com';
        $user = User::factory()->create(['email' =>  $email]);
        $token = "fakeToken115151651ForTestingResetPssword";
        $emailForReset = $userService->matchResetPasswordLink($user->id, $token);
        $user->delete();
        $this->assertFalse(( $emailForReset == $email));
    }
    /**
     *  reseting a user's password
     *
     * @return void
     */
    public function testResetPassword()
    {
        $userService = new UserService();
        $newPassword =  'testnewpassword';
        $user = User::factory()->create();
        $token = Password::createToken($user);
        $status = $userService->resetPassword(['email' => $user->email, 'password' => $newPassword, 'password_confirmation' =>  $newPassword, 'token' => $token]);
        $user->delete();
        $this->assertTrue(( $status == Password::PASSWORD_RESET));
    }
    /**
     *  trying to reset a user's password with an invalid token
     *
     * @return void
     */
    public function testResetPasswordWithFakeToken()
    {
        $userService = new UserService();
        $newPassword =  'testnewpassword';
        $user = User::factory()->create();
        $token = "jhbjhbjhnjknh1165165165jknjn";
        $status = $userService->resetPassword(['email' => $user->email, 'password' => $newPassword, 'password_confirmation' =>  $newPassword, 'token' => $token]);
        $user->delete();
        $this->assertTrue(( $status == Password::INVALID_TOKEN));
    }
    /**
     *  testing unique email
     *
     * @return void
     */
    public function testUniqueEmail()
    {
        $userService = new UserService();
        $user = User::factory()->make();
        $unique = $userService->uniqueEmail($user->email);
        $this->assertTrue($unique);
    }
    /**
     *  testing check google login credentials
     *
     * @return void
     */
    public function testCheckGoogleLoginCredentials()
    {
        $userService = new UserService();
        $email = 'testemail3@test.com';
        $googleId = '103564879584653265984';
        $user = User::factory()->create(['email' =>  $email ,'linked_by_google' => true,'google_id' => $googleId]);
        $returnedUser = $userService->checkGoogleLoginCredentials($email, $googleId);
        $user->delete();
        $this->assertTrue(( $returnedUser != null));
    }
}
