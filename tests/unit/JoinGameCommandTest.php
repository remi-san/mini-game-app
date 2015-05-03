<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class JoinGameCommandTest extends \PHPUnit_Framework_TestCase {
    use GameObjectMocker;
    use MiniGameAppMocker;
    use MessageAppMocker;

    /**
     * @test
     */
    public function test()
    {
        $user = $this->getApplicationUser(42, 'adam');
        $gameId = 5;
        $message = 'message';

        $command = new JoinGameCommand($user, $gameId, $message);

        $this->assertEquals($user, $command->getUser());
        $this->assertEquals($gameId, $command->getGameId());
        $this->assertEquals($message, $command->getMessage());
    }
} 