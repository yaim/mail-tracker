<?php

namespace Tests\Feature;

use App\Email;
use App\Http\Resources\Email as EmailResource;
use App\Http\Resources\EmailCollection as EmailCollectionResource;
use App\Link;
use App\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewEmailTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp()
    {
        parent::setUp();

        $this->emailRepository = resolve(EmailRepository::class);
    }

    public function testUserCanViewParsedEmail()
    {
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        factory(Email::class)->states('parsed')->create([
            'id'                 => $emailId,
            'content'            => 'We think alike.',
            'parsed_content'     => '<img src="'.route('tracking.email', $emailId).'">We think alike.',
        ]);

        $response = $this->get(route('email.show-parsed', $emailId));

        $response->assertStatus(200);
        $response->assertSee('We think alike.');
        $response->assertSee('<img src="'.route('tracking.email', $emailId).'">');
    }

    public function testUserCannotViewRawEmail()
    {
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        factory(Email::class)->states('raw')->create([
            'id' => $emailId,
        ]);

        $response = $this->get(route('email.show-parsed', $emailId));

        $response->assertStatus(404);
    }

    public function testOpeningLinkRedirectToCorrectAddress()
    {
        $linkId = 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy';

        $link = factory(Link::class)->create([
            'id'      => $linkId,
            'address' => 'http://www.johnnycash.com/',
        ]);

        $response = $this->get(route('tracking.links', $linkId));

        $response->assertRedirect('http://www.johnnycash.com/');
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

    public function testUserCanListOwnedPostedEmails()
    {
        $user = factory(User::class)->create(['id' => 100]);
        $emails = (new EmailCollectionResource(collect([
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
            factory(Email::class)->states('parsed')->create(['user_id' => 100]),
        ])))->toArray(null);

        $response = $this->actingAs($user, 'api')->get(route('email.index'));

        $response->assertStatus(200)
                 ->assertExactJson(['data' => $emails->toArray()]);
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
                   ->assertExactJson(['data' => []]);
        $singleEmail->assertStatus(404);
    }
}
