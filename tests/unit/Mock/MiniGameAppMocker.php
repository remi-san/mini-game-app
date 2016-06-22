<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGame\PlayerOptions;
use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\CreatePlayerCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Command\StartGameCommand;
use MiniGameApp\Manager\PlayerManager;
use MiniGameApp\Repository\GameRepository;
use RemiSan\Context\Context;

trait MiniGameAppMocker
{
    /**
     * Returns a mini-game Manager
     *
     * @param  MiniGame $miniGame
     * @return GameRepository
     */
    public function getMiniGameManager(MiniGame $miniGame = null)
    {
        $mg = \Mockery::mock('\\MiniGameApp\\Repository\\GameRepository');
        $mg->shouldReceive('createMiniGame')->andReturn($miniGame);
        $mg->shouldReceive('saveMiniGame')->andReturn($miniGame);
        $mg->shouldReceive('getMiniGameId')->andReturn($miniGame);
        $mg->shouldReceive('getActiveMiniGameForPlayer')->andReturn($miniGame);

        return $mg;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId $playerId
     * @param  GameOptions $options
     * @param  Context     $context
     * @return CreateGameCommand
     */
    public function getCreateGameCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        GameOptions $options = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\CreateGameCommand');
        $command->shouldReceive('getOptions')->andReturn($options);
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @param  string     $move
     * @param  Context    $context
     * @return GameMoveCommand
     */
    public function getGameMoveCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        $move = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\GameMoveCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getMove')->andReturn($move);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId $playerId
     * @param  Context $context
     * @return StartGameCommand
     */
    public function getStartGameCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\StartGameCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId  $playerId
     * @param  PlayerOptions $options
     * @param  Context $context
     * @return GameMoveCommand
     */
    public function getJoinGameCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        PlayerOptions $options = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\JoinGameCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getPlayerOptions')->andReturn($options);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @param  Context    $context
     * @return LeaveGameCommand
     */
    public function getLeaveGameCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\LeaveGameCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @param  Context    $context
     * @return CreatePlayerCommand
     */
    public function getCreatePlayerCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        Context $context = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\CreatePlayerCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getContext')->andReturn($context);
        return $command;
    }
}
