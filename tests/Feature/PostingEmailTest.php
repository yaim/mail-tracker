<?php

namespace Tests\Feature;

use App\Mail\RawMailable;
use App\Repositories\Contracts\EmailRepositoryInterface as EmailRepository;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class PostingEmailTest extends TestCase
{
    use RefreshDatabase;

    protected $emailRepository;

    protected function setUp()
    {
        parent::setUp();

        $this->emailRepository = resolve(EmailRepository::class);
    }

    protected function postEmail($params = [], $byUser = true)
    {
        $testCase = $this;

        if($byUser) {
            $testCase = $testCase->actingAs(factory(User::class)->create(), 'api');
        }

        return $testCase->post(route('email.store', $params));
    }

    protected function assertValidationError($response, $error)
    {
        $response->assertStatus(302)
                 ->assertSessionHasErrors($error);

        return $this;
    }

    protected function assertNoEmailPosted()
    {
        $this->assertEquals($this->emailRepository->count(), 0);

        return $this;
    }

    protected function getEmailParams(array $customParams = [])
    {
        return array_filter($customParams + [
            'from_email_address' => 'j.cash@example.com',
            'to_email_address'   => 'j.carter.cash@example.com',
            'subject'            => 'Happy Birthday Princess',
            'content'            => 'We get old and get use to each other. We think alike.',
        ]);
    }

    public function testUnauthenticatedUserCannotPostEmailAndRedirectsToLogin()
    {
        $response = $this->postEmail($this->getEmailParams(), false);

        $this->assertNoEmailPosted();
        $response->assertRedirect(route('login'));
    }

    public function testAuthenticatedUserCanPostEmail()
    {
        Mail::fake();

        $responseEmailData = $this->getEmailParams([
            'from_email_address' => null,
            'to_email_address'   => null,
            'from'               => 'j.cash@example.com',
            'to'                 => 'j.carter.cash@example.com',
        ]);

        $response = $this->postEmail($this->getEmailParams());

        $this->assertEquals($this->emailRepository->count(), 1);

        $response->assertStatus(201)->assertJson([
            'data' => $responseEmailData,
        ]);

        $emailID = $response->decodeResponseJson()['data']['id'];

        Mail::assertSent(RawMailable::class, function ($mailable) use($emailID) {
            $mailable->build();

            return $mailable->hasFrom('j.cash@example.com')
                   && $mailable->hasTo('j.carter.cash@example.com')
                   && $mailable->getParsedEmail()->id == $emailID;
        });
    }

    public function testFromEmailAddressIsRequiredToPostEmail()
    {
        $email = $this->getEmailParams(['from_email_address' => null]);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'from_email_address' => 'The from email address field is required.',
        ])->assertNoEmailPosted();
    }

    public function testFromEmailAddressMustBeAValidEmailAddressToPostEmail()
    {
        $email = $this->getEmailParams(['from_email_address' => 'invalid-email']);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'from_email_address' => 'The from email address must be a valid email address.',
        ])->assertNoEmailPosted();
    }

    public function testFromEmailAddressMayNotBeGreaterThan255CharactersToPostEmail()
    {
        $email = $this->getEmailParams(['from_email_address' => 'veeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeerylong@example.com']);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'from_email_address' => 'The from email address may not be greater than 255 characters.',
        ])->assertNoEmailPosted();
    }

    public function testToEmailAddressIsRequiredToPostEmail()
    {
        $email = $this->getEmailParams(['to_email_address' => null]);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'to_email_address' => 'The to email address field is required.',
        ])->assertNoEmailPosted();
    }

    public function testToEmailAddressMustBeAValidEmailAddressToPostEmail()
    {
        $email = $this->getEmailParams(['to_email_address' => 'invalid-email']);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'to_email_address' => 'The to email address must be a valid email address.',
        ])->assertNoEmailPosted();
    }

    public function testToEmailAddressMayNotBeGreaterThan255CharactersToPostEmail()
    {
        $email = $this->getEmailParams(['to_email_address' => 'veeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeeerylong@example.com']);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'to_email_address' => 'The to email address may not be greater than 255 characters.',
        ])->assertNoEmailPosted();
    }

    public function testSubjectIsRequiredToPostEmail()
    {
        $email = $this->getEmailParams(['subject' => null]);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'subject' => 'The subject field is required.',
        ])->assertNoEmailPosted();
    }

    public function testSubjectMayNotBeGreaterThan255CharactersToPostEmail()
    {
        $email = $this->getEmailParams(['subject' => 'Helloooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooooo']);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'subject' => 'The subject may not be greater than 255 characters.',
        ])->assertNoEmailPosted();
    }

    public function testContentIsRequiredToPostEmail()
    {
        $email = $this->getEmailParams(['content' => null]);

        $response = $this->postEmail($email);

        $this->assertValidationError($response, [
            'content' => 'The content field is required.',
        ])->assertNoEmailPosted();
    }
}
