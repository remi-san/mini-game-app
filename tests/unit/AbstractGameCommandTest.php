<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\ConcreteGameCommand;

class AbstractGameCommandTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function test()
    {
        $gameId = $this->getMiniGameId(666);

        $command = new ConcreteGameCommand($gameId);

        $this->assertEquals('TEST', $command->getCommandName());
    }
}
