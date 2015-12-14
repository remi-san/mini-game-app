<?php
namespace MiniGameApp\Test;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class MiniGameAppErrorEventTest extends \PHPUnit_Framework_TestCase
{
    use MiniGameAppMocker;
    use GameObjectMocker;

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var MiniGameId
     */
    private $gameId;

    public function setUp()
    {
        $this->playerId = $this->getPlayerId(42);

        $this->gameId = $this->getMiniGameId(666);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test()
    {
        $event = new MiniGameAppErrorEvent($this->gameId, $this->playerId, 'message');

        $this->assertEquals($this->gameId, $event->getGameId());
        $this->assertEquals($this->playerId, $event->getPlayerId());
        $this->assertEquals('message', $event->getAsMessage());
    }
}