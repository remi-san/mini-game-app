<?php
namespace MiniGameApp\Test;

use MessageApp\Test\Mock\MessageAppMocker;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;
use MiniGame\Exceptions\IllegalMoveException;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Application\Handler\MiniGameCommandHandler;
use MiniGameApp\Application\MiniGameResponseBuilder;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MiniGameAppMocker;
    use GameObjectMocker;
    use MessageAppMocker;

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var Player
     */
    private $player;

    /**
     * @var MiniGameId
     */
    private $gameId;

    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * @var MiniGameResponseBuilder
     */
    private $responseBuilder;

    public function setUp()
    {
        $this->playerId = $this->getPlayerId(42);

        $this->player = $this->getPlayer($this->playerId, 'Adams');

        $this->gameId = $this->getMiniGameId(666);

        $this->gameManager = \Mockery::mock('\\MiniGameApp\\Manager\\GameManager');

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
        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $executor->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));
    }

    /**
     * @test
     */
    public function testJoinGame()
    {
        $command = $this->getJoinGameCommand($this->gameId, $this->playerId);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $executor->handleJoinGameCommand($command);
    }

    /**
     * @test
     */
    public function testLeaveGame()
    {
        $command = $this->getLeaveGameCommand($this->gameId, $this->playerId);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $executor->handleLeaveGameCommand($command);
    }

    /**
     * @test
     */
    public function testCreateGame()
    {
        $message = 'start';
        $options = $this->getGameOptions();
        $options->shouldReceive('getId')->andReturn($this->gameId);
        $options->shouldReceive('getPlayers')->andReturn(array($this->getPlayer($this->playerId)));

        $command = $this->getCreateGameCommand($this->gameId, $options, $message);
        $this->gameManager->shouldReceive('createMiniGame')->with($this->gameId, $options)->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $message)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleCreateGameCommand($command);

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
        $options->shouldReceive('getId')->andReturn($this->gameId);
        $options->shouldReceive('getPlayers')->andReturn(array($this->getPlayer($this->playerId)));

        $command = $this->getCreateGameCommand($this->gameId, $options, $message);

        $exception = new \Exception($exceptionMessage);

        $this->gameManager->shouldReceive('createMiniGame')->andThrow($exception);

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $exceptionMessage)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleCreateGameCommand($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMove()
    {
        $resultText = 'result';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);
        $miniGame = $this->getMiniGame($this->gameId, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager
            ->shouldReceive('saveMiniGame')
            ->with($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $move)
            ->andReturn($result)
            ->once();
        $this->gameManager
            ->shouldReceive('getMiniGame')
            ->with($this->gameId)
            ->andReturn($miniGame)
            ->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleGameMoveCommand($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMoveWithEnding()
    {
        $resultText = 'end';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);
        $miniGame = $this->getMiniGame($this->gameId, 'game');
        $result = $this->getEndGame($resultText);

        $this->gameManager
            ->shouldReceive('deleteMiniGame')
            ->with($this->gameId)
            ->once();
        $this->gameManager
            ->shouldReceive('getMiniGame')
            ->with($this->gameId)
            ->andReturn($miniGame)
            ->once();

        $miniGame->shouldReceive('play')->with($this->playerId, $move)->andReturn($result)->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleGameMoveCommand($command);

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
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);
        $miniGame = $this->getMiniGame($this->gameId, 'game');
        $result = $this->getGameResult($resultText);

        $this->gameManager
            ->shouldReceive('getMiniGame')
            ->with($this->gameId)
            ->andReturn($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $move)
            ->andThrow(new IllegalMoveException($this->playerId, $this->gameId, $result, $move, $exceptionText));

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $messageText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleGameMoveCommand($command);

        $this->assertEquals($expectedResponse, $response);
    }

    /**
     * @test
     */
    public function testGameMoveWithGameNotFoundException()
    {
        $resultText = 'You have to start/join a game first!';
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);

        $this->gameManager
            ->shouldReceive('getMiniGame')
            ->with($this->gameId)
            ->andThrow('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException')
            ->once();

        $expectedResponse = \Mockery::mock('\\MessageApp\\Application\\Response');
        $this->responseBuilder
            ->shouldReceive('buildResponse')
            ->with($this->playerId, $resultText)
            ->andReturn($expectedResponse);

        $executor = new MiniGameCommandHandler($this->gameManager, $this->responseBuilder);
        $response = $executor->handleGameMoveCommand($command);

        $this->assertEquals($expectedResponse, $response);
    }
}
