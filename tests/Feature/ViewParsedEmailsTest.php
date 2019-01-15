<?php

namespace Tests\Feature;

use App\Email;
use App\Link;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ViewParsedEmailsTest extends TestCase
{
    use RefreshDatabase;

    public function testUserCanViewParsedEmail()
    {
        $emailId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';

        factory(Email::class)->states('parsed')->create([
            'id'                 => $emailId,
            'content'            => 'We think alike.',
            'parsed_content'     => '<img src="'.route('tracking.email', $emailId).'">We think alike.',
        ]);

        $response = $this->get(route('parsed-email.show', $emailId));

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

        $response = $this->get(route('parsed-email.show', $emailId));

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
}
