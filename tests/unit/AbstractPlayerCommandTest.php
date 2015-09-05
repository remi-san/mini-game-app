<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\ConcretePlayerCommand;

class AbstractPlayerCommandTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test()
    {
        $gameId = $this->getMiniGameId(666);
        $playerId = $this->getPlayerId(42);

        $command = new ConcretePlayerCommand($gameId, $playerId);

        $this->assertEquals($playerId, $command->getPlayerId());
        $this->assertEquals('TEST', $command->getCommandName());
    }
}
