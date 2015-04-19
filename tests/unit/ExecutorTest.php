<?php
namespace MiniGameApp\Test;

use MiniGame\Player;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Executor\MiniGameCommandExecutor;
use MiniGameApp\ApplicationUser;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class ExecutorTest extends \PHPUnit_Framework_TestCase {
    use MiniGameAppMocker;
    use GameObjectMocker;

    /**
     * @var ApplicationUser
     */
    private $user;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * @var PlayerManager
     */
    private $playerManager;

    public function setUp()
    {
        $this->player = $this->getPlayer(1, 'John');
        $this->user = $this->getApplicationUser(1, 'John');

        $this->gameManager = \Mockery::mock('\\MiniGameApp\\Manager\\GameManager');
        $this->playerManager = \Mockery::mock('\\MiniGameApp\\Manager\\PlayerManager');
        $this->playerManager->shouldReceive('getPlayer')->andReturn($this->player);
    }

    /**
     * @test
     */
    public function testUnrecognizedCommand()
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\ApplicationCommand');
        $command->shouldReceive('getUser')->andReturn($this->user);

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals('Unrecognized command!', $response->getMessage());
    }

    /**
     * @test
     */
    public function testJoinGame()
    {
        $command = $this->getJoinGameCommand($this->user);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $executor->execute($command);
    }
} 