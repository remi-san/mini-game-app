<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class GameMoveCommandTest extends \PHPUnit_Framework_TestCase
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
        $user = $this->getPlayer(42, 'adam');
        $move = $this->getMove('move');

        $command = new GameMoveCommand($user, $move);

        $this->assertEquals($user, $command->getPlayer());
        $this->assertEquals($move, $command->getMove());
    }
}
