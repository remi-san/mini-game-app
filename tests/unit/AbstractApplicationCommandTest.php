<?php
namespace MiniGameApp\Test;

use MiniGameApp\Application\Command\AbstractApplicationCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class TestCommand extends AbstractApplicationCommand {}

class AbstractApplicationCommandTest extends \PHPUnit_Framework_TestCase
{
    use MiniGameAppMocker;

    /**
     * @test
     */
    public function testWithTwitterMessage()
    {
        $user = $this->getApplicationUser(42, 'Adam');

        $command = new TestCommand($user);
        $returnUser = $command->getUser();

        $this->assertEquals($user, $returnUser);
    }
}