<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\CreatePlayerCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CreatePlayerCommandTest extends \PHPUnit_Framework_TestCase
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

        $command = new CreatePlayerCommand($user);

        $this->assertEquals($user, $command->getPlayer());
        $this->assertEquals(CreatePlayerCommand::NAME, $command->getCommandName());
    }
}
