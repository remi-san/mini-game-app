<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CreateGameCommandTest extends \PHPUnit_Framework_TestCase {
    use GameObjectMocker;
    use MiniGameAppMocker;
    use MessageAppMocker;

    /**
     * @test
     */
    public function test()
    {
        $user = $this->getApplicationUser(42, 'adam');
        $options = $this->getGameOptions();
        $message = 'message';

        $command = new CreateGameCommand($user, $options, $message);

        $this->assertEquals($user, $command->getUser());
        $this->assertEquals($options, $command->getOptions());
        $this->assertEquals($message, $command->getMessage());
    }
} 