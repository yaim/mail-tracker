<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function assertNoUserCreated()
    {
        $this->assertEquals(User::count(), 0);

        return $this;
    }

    protected function assertValidationError($response, $error)
    {
        $key = array_keys($error)[0];
        $errors = app('session.store')->get('errors')->getBag('default')->get($key);

        $response->assertStatus(302);
        $this->assertContains($error[$key], $errors);

        return $this;
    }

    protected function registerUser($params = [], $byUser = false)
    {
        $testCase = $this;

        if ($byUser) {
            $testCase = $testCase->actingAs(factory(User::class)->create(), 'api');
        }

        return $testCase->post(route('register', $params));
    }

    protected function getUserParams(array $customParams = [])
    {
        return array_filter($customParams + [
            'name'                  => 'Johnny Cash',
            'email'                 => 'j.cash@example.com',
            'password'              => 'W@lk th3 L1N3',
            'password_confirmation' => 'W@lk th3 L1N3',
        ]);
    }

    public function testGuestSeeGuestLinks()
    {
        $response = $this->get(route('home.welcome'));

        $response->assertStatus(200);
        $response->assertSee(route('login'));
        $response->assertSee(route('register'));
        $response->assertDontSee(route('home.index'));
    }

    public function testUserSeeUserLinks()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('home.welcome'));

        $response->assertStatus(200);
        $response->assertDontSee(route('login'));
        $response->assertDontSee(route('register'));
        $response->assertSee(route('home.index'));
    }

    public function testUserCantSeeRegisterPage()
    {
        $user = factory(User::class)->create();

        $this->get(route('register'))->assertStatus(200);
        $response = $this->actingAs($user)->get(route('register'));

        $response->assertRedirect(route('home.index'));
    }

    public function testUserCantSeeLoginPage()
    {
        $user = factory(User::class)->create();

        $this->get(route('login'))->assertStatus(200);
        $response = $this->actingAs($user)->get(route('login'));

        $response->assertRedirect(route('home.index'));
    }

    public function testUserCantSeeResetPasswordPage()
    {
        $user = factory(User::class)->create();

        $this->get(route('password.reset', 'token'))->assertStatus(200);
        $response = $this->actingAs($user)->get(route('password.reset', 'token'));

        $response->assertRedirect(route('home.index'));
    }

    public function testUserCantSeeForgetPasswordPage()
    {
        $user = factory(User::class)->create();

        $this->get(route('password.request'))->assertStatus(200);
        $response = $this->actingAs($user)->get(route('password.request'));

        $response->assertRedirect(route('home.index'));
    }

    public function testUserCanSeeDashboard()
    {
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get(route('home.index'));

        $response->assertSee('Dashboard');
    }

    public function testEmailIsRequiredToRegisterUser()
    {
        $user = $this->getUserParams(['email' => null]);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'email' => 'The email field is required.',
        ])->assertNoUserCreated();
    }

    public function testEmailMustBeAValidEmailAddressToRegisterUser()
    {
        $user = $this->getUserParams(['email' => 'invalid-email']);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'email' => 'The email must be a valid email address.',
        ])->assertNoUserCreated();
    }

    public function testEmailMayNotBeGreaterThan255CharactersToRegisterUser()
    {
        $user = $this->getUserParams(['email' => 'veeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeerylong@example.com']);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'email' => 'The email may not be greater than 255 characters.',
        ])->assertNoUserCreated();
    }

    public function testEmailMustBeUniqueToRegisterUser()
    {
        factory(User::class)->create(['email' => 'j.cash@example.com']);
        $user = $this->getUserParams();

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'email' => 'The email has already been taken.',
        ]);
    }

    public function testNameIsRequiredToRegisterUser()
    {
        $user = $this->getUserParams(['name' => null]);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'name' => 'The name field is required.',
        ])->assertNoUserCreated();
    }

    public function testNameMayNotBeGreaterThan255CharactersToRegisterUser()
    {
        $user = $this->getUserParams(['name' => 'Jooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooohnny']);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'name' => 'The name may not be greater than 255 characters.',
        ])->assertNoUserCreated();
    }

    public function testPasswordIsRequiredToRegisterUser()
    {
        $user = $this->getUserParams(['password' => null]);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'password' => 'The password field is required.',
        ])->assertNoUserCreated();
    }

    public function testPasswordMustBeAtLeast6CharactersToRegisterUser()
    {
        $user = $this->getUserParams(['password' => 'Short']);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'password' => 'The password must be at least 6 characters.',
        ])->assertNoUserCreated();
    }

    public function testPasswordConfirmationMustMatchToRegisterUser()
    {
        $user = $this->getUserParams(['password' => 'Walk The Line']);

        $response = $this->registerUser($user);

        $this->assertValidationError($response, [
            'password' => 'The password confirmation does not match.',
        ])->assertNoUserCreated();
    }

    public function testUserCanBeRegistered()
    {
        $user = $this->getUserParams();

        $this->assertEquals(User::count(), 0);
        $response = $this->registerUser($user);

        $this->assertEquals(User::count(), 1);
    }
}
