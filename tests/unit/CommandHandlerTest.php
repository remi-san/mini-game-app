<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Exceptions\IllegalMoveException;
use MiniGame\Player;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Executor\MiniGameCommandHandler;
use MiniGameApp\Application\MiniGameResponseBuilder;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MiniGameAppMocker;
    use GameObjectMocker;
    use MessageAppMocker;

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

    /**
     * @var MiniGameResponseBuilder
     */
    private $responseBuilder;

    public function setUp()
    {
        $this->player = $this->getPlayer(42, 'Adams');

        $this->gameManager = \Mockery::mock('\\MiniGameApp\\Manager\\GameManager');

        $this->playerManager = \Mockery::mock('\\MiniGameApp\\Manager\\PlayerManager');

        $this->responseBuilder = \Mockery::mock('\\MiniGameApp\\Application\\MiniGameResponseBuilder');
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testLogger()
    {
        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $executor->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));
    }

    /**
     * @test
     */
    public function testIllegalCommand()
    {
        $command = \Mockery::mock('\\League\\Tactician\\Plugins\\NamedCommand\\NamedCommand');

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor->handle($command);
    }

    /**
     * @test
     */
    public function testIllegalPlayer()
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\GameCommand');
        $command->shouldReceive('getPlayer')->andReturn(null);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor->handle($command);
    }

    /**
     * @test
     */
    public function testUnrecognizedCommand()
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\GameCommand');
        $command->shouldReceive('getPlayer')->andReturn($this->player);

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, 'Unrecognized command!')
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testCreatePlayer()
    {
        $message = 'Welcome!';
        $command = $this->getCreatePlayerCommand($this->player);
        $this->playerManager->shouldReceive('save')->with($this->player)->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $message)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testErrorCreatingPlayer()
    {
        $message = 'Could not create the player!';
        $command = $this->getCreatePlayerCommand($this->player);
        $this->playerManager
            ->shouldReceive('save')
            ->with($this->player)
            ->andThrow('\\MiniGameApp\\Manager\\Exceptions\\PlayerException');

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $message)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testJoinGame()
    {
        $command = $this->getJoinGameCommand($this->player);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $executor->handle($command);
    }

    /**
     * @test
     */
    public function testLeaveGame()
    {
        $command = $this->getLeaveGameCommand($this->player);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $executor->handle($command);
    }

    /**
     * @test
     */
    public function testCreateGame()
    {
        $message = 'start';
        $options = $this->getGameOptions();
        $command = $this->getCreateGameCommand($this->player, $options, $message);
        $this->gameManager->shouldReceive('createMiniGame')->with($options)->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $message)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testCreateGameWithException()
    {
        $message = 'start';
        $exceptionMessage = 'exception';
        $options = $this->getGameOptions();
        $command = $this->getCreateGameCommand($this->player, $options, $message);

        $exception = new \Exception($exceptionMessage);

        $this->gameManager->shouldReceive('createMiniGame')->andThrow($exception);

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $exceptionMessage)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMove()
    {
        $resultText = 'result';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->player, $move);
        $miniGame = $this->getMiniGame(42, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager
            ->shouldReceive('getActiveMiniGameForPlayer')
            ->with($this->player)
            ->andReturn($miniGame)
            ->once();
        $this->gameManager
            ->shouldReceive('saveMiniGame')
            ->with($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->player, $move)
            ->andReturn($result)
            ->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMoveWithEnding()
    {
        $resultText = 'end';
        $gameId = 42;
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->player, $move);
        $miniGame = $this->getMiniGame($gameId, 'game');
        $result = $this->getEndGame($resultText);

        $this->gameManager
            ->shouldReceive('getActiveMiniGameForPlayer')
            ->with($this->player)
            ->andReturn($miniGame)
            ->once();
        $this->gameManager
            ->shouldReceive('deleteMiniGame')
            ->with($gameId)
            ->once();

        $miniGame->shouldReceive('play')->with($this->player, $move)->andReturn($result)->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMoveWithGameException()
    {
        $exceptionText = 'exception';
        $resultText = 'bad result';
        $messageText = $exceptionText . ' ' . $resultText;

        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->player, $move);
        $miniGame = $this->getMiniGame(42, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager
            ->shouldReceive('getActiveMiniGameForPlayer')
            ->with($this->player)
            ->andReturn($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->player, $move)
            ->andThrow(new IllegalMoveException($this->player, $miniGame, $result, $move, $exceptionText));

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $messageText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMoveWithGameNotFoundException()
    {
        $resultText = 'You have to start/join a game first!';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->player, $move);

        $this->gameManager
            ->shouldReceive('getActiveMiniGameForPlayer')
            ->with($this->player)
            ->andThrow(new GameNotFoundException());

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->player, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->playerManager, $this->responseBuilder);
        $response = $executor->handle($command);

        $this->assertEquals($expectedResponse, $response);
    }
}
