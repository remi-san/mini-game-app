<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class GameMoveCommandTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;
    use MiniGameAppMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test()
    {
        $userId = $this->getPlayerId(42);
        $gameId = $this->getMiniGameId(666);
        $move = $this->getMove('move');

        $command = GameMoveCommand::create($gameId, $userId, $move);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($move, $command->getMove());
        $this->assertEquals(GameMoveCommand::NAME, $command->getCommandName());
    }
}
