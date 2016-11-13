<?php
namespace MiniGameApp\Test;

use Faker\Factory;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;
use MiniGame\Exceptions\IllegalMoveException;
use MiniGame\GameOptions;
use MiniGame\GameResult;
use MiniGame\Move;
use MiniGame\PlayerOptions;
use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\JoinGameCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Command\StartGameCommand;
use MiniGameApp\Error\ErrorEventHandler;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\Exception\GameNotFoundException;
use MiniGameApp\Handler\MiniGameCommandHandler;
use MiniGameApp\MiniGameFactory;
use MiniGameApp\Repository\GameRepository;
use Mockery\Mock;
use RemiSan\Context\Context;

class CommandHandlerTest extends \PHPUnit_Framework_TestCase
{
    /** @var MiniGameId */
    private $gameId;

    /** @var PlayerId */
    private $playerId;

    /** @var Player */
    private $player;

    /** @var Context */
    private $context;

    /** @var Move */
    private $move;

    /** @var GameOptions | Mock */
    private $options;

    /** @var PlayerOptions | Mock */
    private $playerOptions;

    /** @var MiniGame | Mock */
    private $miniGame;

    /** @var MiniGameFactory | Mock */
    private $gameBuilder;

    /** @var GameRepository | Mock */
    private $gameManager;

    /** @var ErrorEventHandler | Mock */
    private $errorHandler;

    /** @var MiniGameCommandHandler */
    private $miniGameCommandHandler;

    public function setUp()
    {
        $faker = Factory::create();

        $this->gameId = MiniGameId::create($faker->uuid);
        $this->playerId = PlayerId::create($faker->uuid);

        $this->miniGame = $this->getMiniGame($this->gameId, $faker->name);
        $this->player = $this->getPlayer($this->playerId, $faker->name);
        $this->move = \Mockery::mock(Move::class);
        $this->options = $this->getGameOptions();
        $this->playerOptions = \Mockery::mock(PlayerOptions::class);
        $this->context = \Mockery::mock(Context::class);

        $this->gameBuilder = \Mockery::mock(MiniGameFactory::class);
        $this->gameManager = \Mockery::mock(GameRepository::class);
        $this->errorHandler = \Mockery::mock(ErrorEventHandler::class);

        $this->miniGameCommandHandler = new MiniGameCommandHandler(
            $this->gameBuilder,
            $this->gameManager,
            $this->errorHandler
        );
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldCreateGame()
    {
        $command = CreateGameCommand::create($this->gameId, $this->playerId, $this->options, $this->context);

        $this->assertItWillCreateGame();
        $this->assertGameStateWillBePersisted();

        $this->miniGameCommandHandler->handleCreateGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailCreatingGame()
    {
        $command = CreateGameCommand::create($this->gameId, $this->playerId, $this->options, $this->context);

        $this->givenGameCannotBeCreated();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleCreateGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldStartGame()
    {
        $command = StartGameCommand::create($this->gameId, $this->playerId, $this->context);

        $this->givenGameExists();

        $this->assertItWillStartTheGame();
        $this->assertGameStateWillBePersisted();

        $this->miniGameCommandHandler->handleStartGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailStartingGame()
    {
        $command = StartGameCommand::create($this->gameId, $this->playerId, $this->context);

        $this->givenGameExists();
        $this->givenItCanNotStartTheGame();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleStartGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldJoinGame()
    {
        $command = JoinGameCommand::create($this->gameId, $this->playerId, $this->playerOptions, $this->context);

        $this->givenGameExists();

        $this->assertPlayerWillBeAddedToGame();
        $this->assertGameStateWillBePersisted();

        $this->miniGameCommandHandler->handleJoinGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailJoiningGame()
    {
        $command = JoinGameCommand::create($this->gameId, $this->playerId, $this->playerOptions, $this->context);

        $this->givenGameExists();
        $this->givenItCanNotAddAPlayerToGame();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleJoinGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldLeaveGame()
    {
        $command = LeaveGameCommand::create($this->gameId, $this->playerId, $this->context);

        $this->givenGameExists();

        $this->assertPlayerWillLeaveGame();
        $this->assertGameStateWillBePersisted();

        $this->miniGameCommandHandler->handleLeaveGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailLeavingGame()
    {
        $command = LeaveGameCommand::create($this->gameId, $this->playerId, $this->context);

        $this->givenGameExists();
        $this->givenPlayerCanNotLeaveGame();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleLeaveGameCommand($command);
    }

    /**
     * @test
     */
    public function itShouldPlay()
    {
        $command = GameMoveCommand::create($this->gameId, $this->playerId, $this->move, $this->context);

        $this->givenGameExists();

        $this->assertPlayerWillPlay();
        $this->assertGameStateWillBePersisted();

        $this->miniGameCommandHandler->handleGameMoveCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailPlaying()
    {
        $command = GameMoveCommand::create($this->gameId, $this->playerId, $this->move, $this->context);

        $this->givenGameExists();
        $this->givenPlayerCanNotPlay();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleGameMoveCommand($command);
    }

    /**
     * @test
     */
    public function itShouldFailPlayingIfGameDoesNotExist()
    {
        $command = GameMoveCommand::create($this->gameId, $this->playerId, $this->move, $this->context);

        $this->givenGameDoesNotExist();

        $this->assertErrorWillBeHandled();
        $this->assertGameStateWontChange();

        $this->miniGameCommandHandler->handleGameMoveCommand($command);
    }

    /**
     * @param MiniGameId $gameId
     * @param string     $name
     *
     * @return MiniGame
     */
    private function getMiniGame($gameId, $name)
    {
        /** @var MiniGame | Mock $miniGame */
        $miniGame = \Mockery::mock(MiniGame::class);
        $miniGame->shouldReceive('getId')->andReturn($gameId);
        $miniGame->shouldReceive('getName')->andReturn($name);

        return $miniGame;
    }

    /**
     * @param PlayerId $playerId
     * @param string   $name
     *
     * @return Player
     */
    private function getPlayer($playerId, $name)
    {
        /** @var Player | Mock $player */
        $player = \Mockery::mock(Player::class);
        $player->shouldReceive('getId')->andReturn($playerId);
        $player->shouldReceive('getName')->andReturn($name);

        return $player;
    }

    /**
     * @return GameOptions
     */
    private function getGameOptions()
    {
        /** @var GameOptions | Mock $options */
        $options = \Mockery::mock(GameOptions::class);
        $options->shouldReceive('getId')->andReturn($this->gameId);
        $options->shouldReceive('getPlayerOptions')->andReturn([$this->playerOptions]);

        return $options;
    }

    private function assertItWillStartTheGame()
    {
        $this->miniGame
            ->shouldReceive('startGame')
            ->with($this->playerId)
            ->once();
    }

    private function assertGameStateWillBePersisted()
    {
        $this->gameManager
            ->shouldReceive('save')
            ->with($this->miniGame)
            ->once();
    }

    private function givenGameExists()
    {
        $this->gameManager
            ->shouldReceive('load')
            ->with($this->gameId)
            ->andReturn($this->miniGame);
    }

    private function assertGameStateWontChange()
    {
        $this->gameManager
            ->shouldReceive('save')
            ->with($this->miniGame)
            ->never();
    }

    private function givenItCanNotStartTheGame()
    {
        $this->miniGame
            ->shouldReceive('startGame')
            ->with($this->playerId)
            ->andThrow(\Exception::class);
    }

    private function assertErrorWillBeHandled()
    {
        $this->errorHandler
            ->shouldReceive('handle')
            ->with(\Mockery::on(function ($errorEvent) {
                return $errorEvent instanceof MiniGameAppErrorEvent;
            }), $this->context)
            ->once();
    }

    private function assertPlayerWillBeAddedToGame()
    {
        $this->miniGame
            ->shouldReceive('addPlayerToGame')
            ->with($this->playerOptions)
            ->once();
    }

    private function givenItCanNotAddAPlayerToGame()
    {
        $this->miniGame
            ->shouldReceive('addPlayerToGame')
            ->with($this->playerId)
            ->andThrow(\Exception::class);
    }

    private function assertPlayerWillLeaveGame()
    {
        $this->miniGame
            ->shouldReceive('leaveGame')
            ->with($this->playerId)
            ->once();
    }

    private function givenPlayerCanNotLeaveGame()
    {
        $this->miniGame
            ->shouldReceive('leaveGame')
            ->with($this->playerId)
            ->andThrow(\Exception::class);
    }

    private function assertItWillCreateGame()
    {
        $this->gameBuilder
            ->shouldReceive('createMiniGame')
            ->with($this->gameId, $this->playerId, $this->options)
            ->andReturn($this->miniGame)
            ->once();
    }

    private function givenGameCannotBeCreated()
    {
        $this->gameBuilder
            ->shouldReceive('createMiniGame')
            ->andThrow(\Exception::class);
    }

    private function assertPlayerWillPlay()
    {
        $this->miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $this->move)
            ->andReturn(\Mockery::mock(GameResult::class))
            ->once();
    }

    private function givenPlayerCanNotPlay()
    {
        $this->miniGame
            ->shouldReceive('play')
            ->with($this->playerId, $this->move)
            ->andThrow(new IllegalMoveException($this->move));
    }

    private function givenGameDoesNotExist()
    {
        $this->gameManager
            ->shouldReceive('load')
            ->with($this->gameId)
            ->andThrow(GameNotFoundException::class);
    }
}
