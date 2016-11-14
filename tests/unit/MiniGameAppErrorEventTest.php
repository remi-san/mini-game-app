<?php
namespace MiniGameApp\Test;

use Faker\Factory;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGameApp\Event\MiniGameAppErrorEvent;

class MiniGameAppErrorEventTest extends \PHPUnit_Framework_TestCase
{
    /** @var PlayerId */
    private $playerId;

    /** @var MiniGameId */
    private $gameId;

    public function setUp()
    {
        $faker = Factory::create();

        $this->playerId = PlayerId::create($faker->uuid);
        $this->gameId = MiniGameId::create($faker->uuid);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldBuildTheError()
    {
        $event = new MiniGameAppErrorEvent($this->gameId, $this->playerId, 'message');

        $this->assertEquals($this->gameId, $event->getGameId());
        $this->assertEquals($this->playerId, $event->getPlayerId());
        $this->assertEquals('message', $event->getAsMessage());
    }
}
