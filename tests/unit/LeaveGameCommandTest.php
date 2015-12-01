<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\LeaveGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class LeaveGameCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = new LeaveGameCommand($gameId, $userId);

        $this->assertEquals($userId, $command->getPlayerId());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals(LeaveGameCommand::NAME, $command->getCommandName());
    }
}
