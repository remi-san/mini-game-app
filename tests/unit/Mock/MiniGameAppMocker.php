<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\CreatePlayerCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;

trait MiniGameAppMocker
{
    /**
     * Returns a mini-game Manager
     *
     * @param  MiniGame $miniGame
     * @return GameManager
     */
    public function getMiniGameManager(MiniGame $miniGame = null)
    {
        $mg = \Mockery::mock('\\MiniGameApp\\Manager\\GameManager');
        $mg->shouldReceive('createMiniGame')->andReturn($miniGame);
        $mg->shouldReceive('saveMiniGame')->andReturn($miniGame);
        $mg->shouldReceive('getMiniGameId')->andReturn($miniGame);
        $mg->shouldReceive('getActiveMiniGameForPlayer')->andReturn($miniGame);

        return $mg;
    }

    /**
     * @param MiniGameId $gameId
     * @param PlayerId $playerId
     * @param GameOptions $options
     * @param string $message
     * @return CreateGameCommand
     */
    public function getCreateGameCommand(
        MiniGameId $gameId = null,
        PlayerId $playerId = null,
        GameOptions $options = null,
        $message = null
    ) {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\CreateGameCommand');
        $command->shouldReceive('getOptions')->andReturn($options);
        $command->shouldReceive('getMessage')->andReturn($message);
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @param  string     $move
     * @return GameMoveCommand
     */
    public function getGameMoveCommand(MiniGameId $gameId = null, PlayerId $playerId = null, $move = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\GameMoveCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getMove')->andReturn($move);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId  $playerId
     * @return GameMoveCommand
     */
    public function getJoinGameCommand(MiniGameId $gameId = null, PlayerId $playerId = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\JoinGameCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @return LeaveGameCommand
     */
    public function getLeaveGameCommand(MiniGameId $gameId = null, PlayerId $playerId = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\LeaveGameCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        $command->shouldReceive('getGameId')->andReturn($gameId);
        return $command;
    }

    /**
     * @param  MiniGameId $gameId
     * @param  PlayerId   $playerId
     * @return CreatePlayerCommand
     */
    public function getCreatePlayerCommand(MiniGameId $gameId = null, PlayerId $playerId = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Command\\CreatePlayerCommand');
        $command->shouldReceive('getGameId')->andReturn($gameId);
        $command->shouldReceive('getPlayerId')->andReturn($playerId);
        return $command;
    }
}
