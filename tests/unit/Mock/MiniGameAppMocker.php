<?php
namespace MiniGameApp\Test\Mock;

use MessageApp\ApplicationUser;
use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;

trait MiniGameAppMocker {

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
        $mg->shouldReceive('getMiniGame')->andReturn($miniGame);
        $mg->shouldReceive('getActiveMiniGameForPlayer')->andReturn($miniGame);

        return $mg;
    }

    /**
     * Returns a player Manager
     *
     * @param  Player $player
     * @return PlayerManager
     */
    public function getPlayerManager(Player $player = null)
    {
        $mg = \Mockery::mock('\\MiniGameApp\\Manager\\PlayerManager');
        $mg->shouldReceive('create')->andReturn($player);
        $mg->shouldReceive('save')->andReturn($player);
        $mg->shouldReceive('get')->andReturn($player);
        $mg->shouldReceive('getByObject')->andReturn($player);

        return $mg;
    }

    /**
     * @param Player      $player
     * @param GameOptions $options
     * @param string      $message
     * @return CreateGameCommand
     */
    public function getCreateGameCommand(Player $player = null, GameOptions $options = null, $message = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\CreateGameCommand');
        $command->shouldReceive('getPlayer')->andReturn($player);
        $command->shouldReceive('getOptions')->andReturn($options);
        $command->shouldReceive('getMessage')->andReturn($message);
        return $command;
    }

    /**
     * @param Player $player
     * @param string $move
     * @return GameMoveCommand
     */
    public function getGameMoveCommand(Player $player = null, $move = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\GameMoveCommand');
        $command->shouldReceive('getPlayer')->andReturn($player);
        $command->shouldReceive('getMove')->andReturn($move);
        return $command;
    }

    /**
     * @param Player $player
     * @return GameMoveCommand
     */
    public function getJoinGameCommand(Player $player = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\JoinGameCommand');
        $command->shouldReceive('getPlayer')->andReturn($player);
        return $command;
    }
} 