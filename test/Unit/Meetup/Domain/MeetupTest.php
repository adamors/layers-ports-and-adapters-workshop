<?php
declare(strict_types = 1);

namespace Tests\Unit\Meetup\Domain;

use Meetup\Domain\Meetup;
use Meetup\Domain\MeetupId;
use Meetup\Domain\Name;
use Meetup\Domain\Description;

final class MeetupTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_can_be_scheduled_with_just_a_name_description_and_date()
    {
        $id = MeetupId::fromString('03e5efff-12e8-4903-ba24-f2e80e118c90');
        $name = Name::fromString('Name');
        $description = Description::fromString('Description');
        $scheduledFor = new \DateTimeImmutable('now');

        $meetup = Meetup::schedule($id, $name, $description, $scheduledFor);

        $this->assertInstanceOf(Meetup::class, $meetup);
        $this->assertEquals($name, $meetup->name());
        $this->assertEquals($description, $meetup->description());
        $this->assertEquals($scheduledFor, $meetup->scheduledFor());
    }

    /**
     * @test
     */
    public function can_determine_whether_or_not_it_is_upcoming()
    {
        $now = new \DateTimeImmutable();

        $pastMeetup = Meetup::schedule(
            MeetupId::fromString('03e5efff-12e8-4903-ba24-f2e80e118c90'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('-5 days')
        );
        $this->assertFalse($pastMeetup->isUpcoming($now));

        $upcomingMeetup = Meetup::schedule(
            MeetupId::fromString('03e5efff-12e8-4903-ba24-f2e80e118c90'),
            Name::fromString('Name'),
            Description::fromString('Description'),
            new \DateTimeImmutable('+5 days')
        );
        $this->assertTrue($upcomingMeetup->isUpcoming($now));
    }
}