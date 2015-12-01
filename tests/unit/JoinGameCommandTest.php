<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class JoinGameCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = new JoinGameCommand($gameId, $userId);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals(JoinGameCommand::NAME, $command->getCommandName());
    }
}
