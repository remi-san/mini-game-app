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
        $options = $this->getGameOptions();
        $message = 'message';
        $gameId = $this->getMiniGameId(42);

        $command = new CreateGameCommand($gameId, $options, $message);

        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($options, $command->getOptions());
        $this->assertEquals($message, $command->getMessage());
        $this->assertEquals(CreateGameCommand::NAME, $command->getCommandName());
    }
}
