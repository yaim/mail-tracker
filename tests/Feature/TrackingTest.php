<?php

namespace Tests\Feature;

use App\Database\Contracts\Clickable;
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

    protected function assertCountClicks(Clickable $clickable, $count)
    {
        $this->assertEquals($this->reporter->countClicks($clickable), $count);

        return $this;
    }

    public function testLoadingTrackingImageWouldIncreaseEmailClickCount()
    {
        $email = factory(Email::class)->states('parsed')->create([
            'id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        ]);

        $this->assertCountClicks($email, 0);
        $response = $this->get(route('tracking.email', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));

        $response->assertStatus(200);
        $this->assertCountClicks($email, 1);
    }

    public function testLoadingWrongTrackingImageWouldNotIncreaseEmailClickCount()
    {
        $email = factory(Email::class)->states('parsed')->create([
            'id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        ]);

        $this->assertCountClicks($email, 0);
        $response = $this->get(route('tracking.email', 'zzzzzzzz-zzzz-zzzz-zzzz-zzzzzzzzzzzz'));

        $response->assertStatus(404);
        $this->assertCountClicks($email, 0);
    }

    public function testLoadingTrackingImageMultipleTimesWouldIncreaseEmailClickCount()
    {
        $email = factory(Email::class)->states('parsed')->create([
            'id' => 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx',
        ]);

        $this->assertCountClicks($email, 0);

        for ($i = 1; $i <= 5; $i++) { 
            $this->get(route('tracking.email', 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx'));
        }

        $this->assertCountClicks($email, 5);
    }

    public function testLoadingTrackingLinkWouldIncreaseLinkClickCount()
    {
        $link = factory(Link::class)->create([
            'id' => 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy',
        ]);

        $this->assertCountClicks($link, 0);

        $this->get(route('tracking.links', 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy'));

        $this->assertCountClicks($link, 1);
    }

    public function testLoadingTrackingLinkMultipleTimesWouldIncreaseLinkClickCount()
    {
        $link = factory(Link::class)->create([
            'id' => 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy',
        ]);

        $this->assertCountClicks($link, 0);

        for ($i = 1; $i <= 5; $i++) { 
            $this->get(route('tracking.links', 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyyyyyy'));
        }

        $this->assertCountClicks($link, 5);
    }


}
