<?php
namespace MiniGameApp\Test;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\ConcretePlayerCommand;
use RemiSan\Context\Context;

class PlayerCommandTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var MiniGameId
     */
    private $gameId;

    /**
     * @var Context
     */
    private $context;

    public function setUp()
    {
        $this->playerId = $this->getPlayerId(42);
        $this->gameId = $this->getMiniGameId(666);
        $this->context = \Mockery::mock(Context::class);
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
        $playerCommand = new ConcretePlayerCommand($this->gameId, $this->playerId, $this->context);

        $this->assertEquals($this->playerId, $playerCommand->getPlayerId());
        $this->assertEquals($this->gameId, $playerCommand->getGameId());
        $this->assertEquals($this->context, $playerCommand->getContext());
    }
}
