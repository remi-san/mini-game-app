<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Command\JoinGameCommand;
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
        $playerOptions = $this->getPlayerOptions();

        $command = JoinGameCommand::create($gameId, $userId, $playerOptions);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($playerOptions, $command->getPlayerOptions());
        $this->assertEquals(JoinGameCommand::NAME, $command->getCommandName());
    }
}
