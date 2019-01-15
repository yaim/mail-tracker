<?php

namespace Tests\Feature;

use App\Email;
use App\Http\Resources\Email as EmailResource;
use App\Http\Resources\EmailCollection as EmailCollectionResource;
use App\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewReportsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->emailRepository = resolve(EmailRepository::class);
    }

    public function testUserCanGetOwnedPostedEmailData()
    {
        $user = factory(User::class)->create();
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        $email = (new EmailResource(factory(Email::class)->states('parsed')->create([
            'id'                 => $emailId,
            'from_email_address' => 'j.cash@example.com',
            'to_email_address'   => 'j.carter.cash@example.com',
            'subject'            => 'Happy Birthday Princess',
            'content'            => 'We get old and get use to each other. We think alike.',
            'parsed_content'     => '<img src="'.route('tracking.email', $emailId).'">We get old and get use to each other. We think alike.',
            'parsed_at'          => Carbon::parse('23 June 1994'),
            'user_id'            => $user->id,
        ])))->toArray(null);

        $response = $this->actingAs($user, 'api')->get(route('email.show', $emailId));

        $response->assertStatus(200)
                 ->assertExactJson([
                    'data' => $email,
                ]);
    }

    public function testUserCanSeePaginatedListOfOwnedPostedEmails()
    {
        $user = factory(User::class)->create(['id' => 100]);
        $emails = (new EmailCollectionResource(collect([
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
        ])))->toArray(null);
        $emailIndexRoute = route('email.index');

        $response = $this->actingAs($user, 'api')->get($emailIndexRoute);

        $response->assertStatus(200)
                 ->assertJsonFragment([$emails->toArray()])
                 ->assertJson(['meta' => [
                    'current_page' => 1,
                    'from'         => 1,
                    'last_page'    => 1,
                    'path'         => $emailIndexRoute,
                    'per_page'     => 15,
                    'to'           => 5,
                    'total'        => 5,
                 ]])
                 ->assertJson(['links' => [
                    'first' => $emailIndexRoute.'?page=1',
                    'last'  => $emailIndexRoute.'?page=1',
                    'prev'  => null,
                    'next'  => null,
                 ]]);
    }

    public function testUserCanNavigateThroushPaginatedOwnedPostedEmails()
    {
        $user = factory(User::class)->create(['id' => 100]);
        factory(Email::class, 70)->create(['user_id' => 100]);
        $emailIndexRoute = route('email.index');

        $firstPage = $this->actingAs($user, 'api')->get($emailIndexRoute);
        $thirdPage = $this->actingAs($user, 'api')->get($emailIndexRoute.'?page=3');
        $lastPage = $this->actingAs($user, 'api')->get($emailIndexRoute.'?page=5');
        $outPage = $this->actingAs($user, 'api')->get($emailIndexRoute.'?page=20');

        $firstPage->assertStatus(200)
                  ->assertJson(['meta' => [
                    'current_page' => 1,
                    'from'         => 1,
                    'last_page'    => 5,
                    'path'         => $emailIndexRoute,
                    'per_page'     => 15,
                    'to'           => 15,
                    'total'        => 70,
                  ]])
                  ->assertJson(['links' => [
                    'first' => $emailIndexRoute.'?page=1',
                    'last'  => $emailIndexRoute.'?page=5',
                    'prev'  => null,
                    'next'  => $emailIndexRoute.'?page=2',
                  ]]);

        $thirdPage->assertStatus(200)
                  ->assertJson(['meta' => [
                    'current_page' => 3,
                    'from'         => 31,
                    'last_page'    => 5,
                    'path'         => $emailIndexRoute,
                    'per_page'     => 15,
                    'to'           => 45,
                    'total'        => 70,
                  ]])
                  ->assertJson(['links' => [
                    'first' => $emailIndexRoute.'?page=1',
                    'last'  => $emailIndexRoute.'?page=5',
                    'prev'  => $emailIndexRoute.'?page=2',
                    'next'  => $emailIndexRoute.'?page=4',
                  ]]);

        $lastPage->assertStatus(200)
                 ->assertJson(['meta' => [
                    'current_page' => 5,
                    'from'         => 61,
                    'last_page'    => 5,
                    'path'         => $emailIndexRoute,
                    'per_page'     => 15,
                    'to'           => 70,
                    'total'        => 70,
                 ]])
                 ->assertJson(['links' => [
                    'first' => $emailIndexRoute.'?page=1',
                    'last'  => $emailIndexRoute.'?page=5',
                    'prev'  => $emailIndexRoute.'?page=4',
                    'next'  => null,
                 ]]);

        $outPage->assertStatus(200)
                ->assertJson(['data' => []])
                ->assertJson(['meta' => [
                    'current_page' => 20,
                    'from'         => null,
                    'last_page'    => 5,
                    'path'         => $emailIndexRoute,
                    'per_page'     => 15,
                    'to'           => null,
                    'total'        => 70,
                ]])
                ->assertJson(['links' => [
                    'first' => $emailIndexRoute.'?page=1',
                    'last'  => $emailIndexRoute.'?page=5',
                    'prev'  => $emailIndexRoute.'?page=19',
                    'next'  => null,
                ]]);
    }

    public function testUnauthenticatedUserCannotSeePostedEmails()
    {
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        factory(Email::class)->states('parsed')->create([
            'id' => $emailId,
        ]);
        factory(Email::class, 4)->states('parsed')->create();

        $emailsList = $this->get(route('email.index'));
        $singleEmail = $this->get(route('email.show', $emailId));

        $this->assertEquals($this->emailRepository->count(), 5);
        $emailsList->assertRedirect(route('login'));
        $singleEmail->assertRedirect(route('login'));
    }

    public function testUserCannotSeeOthersPostedEmails()
    {
        $june = factory(User::class)->create(['id' => 100]);
        $johnny = factory(User::class)->create(['id' => 101]);
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        factory(Email::class)->states('parsed')->create([
            'id'      => $emailId,
            'user_id' => $june->id,
        ]);
        factory(Email::class, 4)->states('parsed')->create([
            'user_id' => $june->id,
        ]);

        $emailsList = $this->actingAs($johnny, 'api')->get(route('email.index'));
        $singleEmail = $this->actingAs($johnny, 'api')->get(route('email.show', $emailId));

        $this->assertEquals($this->emailRepository->count(), 5);
        $emailsList->assertStatus(200)
                   ->assertJson(['data' => []]);
        $singleEmail->assertStatus(404);
    }
}
