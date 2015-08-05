<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CreateGameCommandTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;
    use MiniGameAppMocker;
    use MessageAppMocker;

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
        $options = $this->getGameOptions();
        $message = 'message';

        $command = new CreateGameCommand($gameId, $userId, $options, $message);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($options, $command->getOptions());
        $this->assertEquals($message, $command->getMessage());
        $this->assertEquals(CreateGameCommand::NAME, $command->getCommandName());
    }
}
