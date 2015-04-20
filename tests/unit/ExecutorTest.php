<?php
namespace MiniGameApp\Test;

use MiniGame\Player;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Executor\MiniGameCommandExecutor;
use MiniGameApp\ApplicationUser;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;
use Symfony\Component\Config\Definition\Exception\Exception;

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

    /**
     * @test
     */
    public function testCreateGame()
    {
        $message = 'start';
        $options = $this->getGameOptions();
        $command = $this->getCreateGameCommand($this->user, $options, $message);
        $this->gameManager->shouldReceive('createMiniGame')->with($options);

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($message, $response->getMessage());
    }

    /**
     * @test
     */
    public function testCreateGameWithException()
    {
        $message = 'start';
        $exceptionMessage = 'exception';
        $options = $this->getGameOptions();
        $command = $this->getCreateGameCommand($this->user, $options, $message);

        $exception = new \Exception($exceptionMessage);

        $this->gameManager->shouldReceive('createMiniGame')->andThrow($exception);

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($exceptionMessage, $response->getMessage());
    }

    /**
     * @test
     */
    public function testGameMove()
    {

    }

    /**
     * @test
     */
    public function testGameMoveWithEnding()
    {

    }

    /**
     * @test
     */
    public function testGameMoveWithGameException()
    {

    }

    /**
     * @test
     */
    public function testGameMoveWithGameNotFoundException()
    {

    }
} 