<?php
namespace MiniGameApp\Test;

use MessageApp\ApplicationUser;
use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Exceptions\IllegalMoveException;
use MiniGame\Player;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Executor\MiniGameCommandExecutor;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class ExecutorTest extends \PHPUnit_Framework_TestCase {
    use MiniGameAppMocker;
    use GameObjectMocker;
    use MessageAppMocker;

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
        $command = \Mockery::mock('\\MessageApp\\Application\\Command\\ApplicationCommand');
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
        $this->gameManager->shouldReceive('createMiniGame')->with($options)->once();

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
        $resultText = 'result';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->user, $move);
        $miniGame = $this->getMiniGame(42, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager->shouldReceive('getActiveMiniGameForPlayer')->with($this->player)->andReturn($miniGame)->once();
        $miniGame->shouldReceive('play')->with($this->player, $move)->andReturn($result)->once();

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($resultText, $response->getMessage());
    }

    /**
     * @test
     */
    public function testGameMoveWithEnding()
    {
        $resultText = 'end';
        $gameId = 42;
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->user, $move);
        $miniGame = $this->getMiniGame($gameId, 'game');
        $result = $this->getEndGame($resultText);

        $this->gameManager->shouldReceive('getActiveMiniGameForPlayer')->with($this->player)->andReturn($miniGame)->twice();
        $this->gameManager->shouldReceive('deleteMiniGame')->with($gameId)->once();

        $miniGame->shouldReceive('play')->with($this->player, $move)->andReturn($result)->once();

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($resultText, $response->getMessage());
    }

    /**
     * @test
     */
    public function testGameMoveWithGameException()
    {
        $exceptionText = 'exception';
        $resultText = 'bad result';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->user, $move);
        $miniGame = $this->getMiniGame(42, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager->shouldReceive('getActiveMiniGameForPlayer')->with($this->player)->andReturn($miniGame)->once();
        $miniGame->shouldReceive('play')->with($this->player, $move)->andThrow(new IllegalMoveException($this->player, $miniGame, $result, $move, $exceptionText));

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($exceptionText . ' ' . $resultText, $response->getMessage());
    }

    /**
     * @test
     */
    public function testGameMoveWithGameNotFoundException()
    {
        $resultText = 'You have to start/join a game first!';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->user, $move);

        $this->gameManager->shouldReceive('getActiveMiniGameForPlayer')->with($this->player)->andThrow(new GameNotFoundException());

        $executor = new MiniGameCommandExecutor($this->gameManager, $this->playerManager);
        $response = $executor->execute($command);

        $this->assertEquals($this->user, $response->getUser());
        $this->assertEquals($resultText, $response->getMessage());
    }
} 