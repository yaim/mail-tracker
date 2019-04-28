<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class PassportAuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        Artisan::call('passport:install');
    }

    public function testUserCanBeAuthorizedToGetUserData()
    {
        $user = factory(User::class)->create([
            'email'    => 'j.cash@example.com',
            'password' => bcrypt('SecretSong'),
            'name'     => 'Johnny Cash',
        ]);

        $this->assertEquals($user->tokens()->count(), 0);

        $this->json('GET', route('api.auth.user'))
             ->assertStatus(401)
             ->assertExactJson(['message' => 'Unauthenticated.']);

        $loginResponse = $this->json('POST', route('api.auth.login'), [
            'email'    => 'j.cash@example.com',
            'password' => 'SecretSong',
        ]);

        $loginResponse->assertStatus(201);
        $loginResponse->assertJsonStructure(['data' => ['token']]);

        $token = json_decode($loginResponse->getContent())->data->token;

        $this->assertEquals($user->tokens()->count(), 1);

        $this->withHeaders([
                'Authorization' => 'Bearer '.$token,
             ])
             ->json('GET', route('api.auth.user'))
             ->assertStatus(200)
             ->assertJson([
                'data' => [
                    'email' => 'j.cash@example.com',
                    'name'  => 'Johnny Cash',
                ],
             ]);
    }

    public function testUserCanRevokeToken()
    {
        $user = factory(User::class)->create([
            'email'    => 'j.cash@example.com',
            'password' => bcrypt('SecretSong'),
            'name'     => 'Johnny Cash',
        ]);

        $token = json_decode($this->json('POST', route('api.auth.login'), [
            'email'    => 'j.cash@example.com',
            'password' => 'SecretSong',
        ])->getContent())->data->token;

        $this->assertEquals($user->tokens()->count(), 1);

        $this->withHeaders([
                'Authorization' => 'Bearer '.$token,
             ])
             ->json('POST', route('api.auth.logout'))
             ->assertStatus(302);

        $this->assertEquals($user->tokens()->count(), 0);
    }
}
