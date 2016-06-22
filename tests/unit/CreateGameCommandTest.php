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
        $gameId = $this->getMiniGameId(42);
        $playerId = $this->getPlayerId(33);

        $command = CreateGameCommand::create($gameId, $playerId, $options);

        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($playerId, $command->getPlayerId());
        $this->assertEquals($options, $command->getOptions());
        $this->assertEquals(CreateGameCommand::NAME, $command->getCommandName());
    }
}
