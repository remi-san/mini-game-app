<?php
namespace MiniGameApp\Test;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;
use MiniGame\Exceptions\IllegalMoveException;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Error\ErrorEventHandler;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\Handler\MiniGameCommandHandler;
use MiniGameApp\MiniGameFactory;
use MiniGameApp\Repository\GameRepository;
use MiniGameApp\Test\Mock\MiniGameAppMocker;

class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    use MiniGameAppMocker;
    use GameObjectMocker;

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
     * @var MiniGameFactory
     */
    private $gameBuilder;

    /**
     * @var GameRepository
     */
    private $gameManager;

    /**
     * @var ErrorEventHandler
     */
    private $errorHandler;

    public function setUp()
    {
        $this->playerId = $this->getPlayerId(42);

        $this->player = $this->getPlayer($this->playerId, 'Adams');

        $this->gameId = $this->getMiniGameId(666);

        $this->gameBuilder = \Mockery::mock('\\MiniGameApp\\MiniGameFactory');

        $this->gameManager = \Mockery::mock('\\MiniGameApp\\Repository\\GameRepository');

        $this->errorHandler = \Mockery::mock(ErrorEventHandler::class);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testJoinGame()
    {
        $command = $this->getJoinGameCommand($this->gameId, $this->playerId);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleJoinGameCommand($command);
    }

    /**
     * @test
     */
    public function testLeaveGame()
    {
        $command = $this->getLeaveGameCommand($this->gameId, $this->playerId);

        $this->setExpectedException('\\InvalidArgumentException');

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
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
        $options->shouldReceive('getPlayerOptions')->andReturn(array($this->getPlayerOptions($this->playerId)));

        $game = $this->getMiniGame();

        $command = $this->getCreateGameCommand($this->gameId, $this->playerId, $options, $message);
        $this->gameBuilder
            ->shouldReceive('createMiniGame')
            ->with($this->gameId, $this->playerId, $options)
            ->andReturn($game)
            ->once();
        $this->gameManager
            ->shouldReceive('save')
            ->with($game)
            ->once();

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleCreateGameCommand($command);
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
        $options->shouldReceive('getPlayerOptions')->andReturn(array($this->getPlayerOptions($this->playerId)));

        $command = $this->getCreateGameCommand($this->gameId, $this->playerId, $options, $message);

        $exception = new \Exception($exceptionMessage);

        $this->gameBuilder->shouldReceive('createMiniGame')->andThrow($exception);

        $this->errorHandler
            ->shouldReceive('handle')
            ->with(\Mockery::on(function ($event) {
                return $event instanceof MiniGameAppErrorEvent;
            }))
            ->once();

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleCreateGameCommand($command);
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
            ->shouldReceive('save')
            ->with($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $move)
            ->andReturn($result)
            ->once();
        $this->gameManager
            ->shouldReceive('load')
            ->with($this->gameId)
            ->andReturn($miniGame)
            ->once();

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleGameMoveCommand($command);
    }

    /**
     * @test
     */
    public function testGameMoveWithGameException()
    {
        $exceptionText = 'exception';

        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);
        $miniGame = $this->getMiniGame($this->gameId, 'game');

        $this->gameManager
            ->shouldReceive('load')
            ->with($this->gameId)
            ->andReturn($miniGame)
            ->once();
        $miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $move)
            ->andThrow(new IllegalMoveException($move, $exceptionText));

        $this->errorHandler
            ->shouldReceive('handle')
            ->with(\Mockery::on(function ($event) {
                return $event instanceof MiniGameAppErrorEvent;
            }))
            ->once();

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleGameMoveCommand($command);
    }

    /**
     * @test
     */
    public function testGameMoveWithGameNotFoundException()
    {
        $move = $this->getMove('a');
        $command = $this->getGameMoveCommand($this->gameId, $this->playerId, $move);

        $this->gameManager
            ->shouldReceive('load')
            ->with($this->gameId)
            ->andThrow('\\MiniGameApp\\Exception\\GameNotFoundException')
            ->once();

        $this->errorHandler
            ->shouldReceive('handle')
            ->with(\Mockery::on(function ($event) {
                return $event instanceof MiniGameAppErrorEvent;
            }))
            ->once();

        $executor = new MiniGameCommandHandler($this->gameBuilder, $this->gameManager, $this->errorHandler);
        $executor->handleGameMoveCommand($command);
    }
}
