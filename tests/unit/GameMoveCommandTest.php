<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class GameMoveCommandTest extends \PHPUnit_Framework_TestCase {
    use GameObjectMocker;
    use MiniGameAppMocker;

    /**
     * @test
     */
    public function test()
    {
        $user = $this->getApplicationUser(42, 'adam');
        $move = $this->getMove('move');

        $command = new GameMoveCommand($user, $move);

        $this->assertEquals($user, $command->getUser());
        $this->assertEquals($move, $command->getMove());
    }
} 