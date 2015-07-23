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
        $user = $this->getPlayer(42, 'adam');
        $name = 'name';

        $command = new ConcreteGameCommand($user, $name);

        $this->assertEquals($user, $command->getPlayer());
        $this->assertEquals($name, $command->getCommandName());
    }
}
