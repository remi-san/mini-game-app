<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CreateGameCommandTest extends \PHPUnit_Framework_TestCase
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
        $options = $this->getGameOptions();
        $message = 'message';
        $gameId = $this->getMiniGameId(42);
        $playerId = $this->getPlayerId(33);

        $command = new CreateGameCommand($gameId, $playerId, $options, $message);

        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($playerId, $command->getPlayerId());
        $this->assertEquals($options, $command->getOptions());
        $this->assertEquals($message, $command->getMessage());
        $this->assertEquals(CreateGameCommand::NAME, $command->getCommandName());
    }
}
