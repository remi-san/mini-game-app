<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\Command\LeaveGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class LeaveGameCommandTest extends \PHPUnit_Framework_TestCase {
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
        $user = $this->getPlayer(42, 'adam');
        $gameId = 666;

        $command = new LeaveGameCommand($user, $gameId);

        $this->assertEquals($user, $command->getPlayer());
        $this->assertEquals($gameId, $command->getGameId());
    }
} 