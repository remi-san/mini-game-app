<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Command\CreatePlayerCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CreatePlayerCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = CreatePlayerCommand::create($gameId, $userId);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals(CreatePlayerCommand::NAME, $command->getCommandName());
    }
}
