<?php

namespace Tests\Feature;

use App\Email;
use App\Link;
use App\Services\Contracts\Tracking\TrackingStatsReporterInterface as TrackingReporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrackingTest extends TestCase
{
    use RefreshDatabase;

    protected $reporter;

    protected function setUp()
    {
        parent::setUp();

        $this->reporter = resolve(TrackingReporter::class);
    }

    public function testLoadingTrackingImageWouldIncreaseEmailClickCount()
    {
        $email = factory(Email::class)->states('parsed')->create([
            'id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        ]);

        $this->assertEquals($this->reporter->countClicks($email), 0);
        $response = $this->get(route('tracking.email', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $response->assertStatus(200);
        $this->assertEquals($this->reporter->countClicks($email), 1);
    }

    public function testLoadingTrackingImageMultipleTimesWouldIncreaseEmailClickCount()
    {
        $email = factory(Email::class)->states('parsed')->create([
            'id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        ]);

        $this->assertEquals($this->reporter->countClicks($email), 0);

        for ($i = 1; $i <= 5; $i++) { 
            $this->get(route('tracking.email', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));
        }

        $this->assertEquals($this->reporter->countClicks($email), 5);
    }

    public function testLoadingTrackingLinkWouldIncreaseLinkClickCount()
    {
        $link = factory(Link::class)->create([
            'id' => 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy',
        ]);

        $this->assertEquals($this->reporter->countClicks($link), 0);

        $this->get(route('tracking.links', 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy'));

        $this->assertEquals($this->reporter->countClicks($link), 1);
    }

    public function testLoadingTrackingLinkMultipleTimesWouldIncreaseLinkClickCount()
    {
        $link = factory(Link::class)->create([
            'id' => 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy',
        ]);

        $this->assertEquals($this->reporter->countClicks($link), 0);

        for ($i = 1; $i <= 5; $i++) { 
            $this->get(route('tracking.links', 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy'));
        }

        $this->assertEquals($this->reporter->countClicks($link), 5);
    }


}
