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
     * @param Player $player
     * @return PlayerManager
     */
    public function getPlayerManager(Player $player = null)
    {
        $manager = \Mockery::mock('\\MiniGameApp\\Manager\\PlayerManager');
        $manager->shouldReceive('getPlayer')->andReturn($player);
        return $manager;
    }

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
     * @param ApplicationUser $user
     * @param GameOptions     $options
     * @param string          $message
     * @return CreateGameCommand
     */
    public function getCreateGameCommand(ApplicationUser $user = null, GameOptions $options = null, $message = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\CreateGameCommand');
        $command->shouldReceive('getUser')->andReturn($user);
        $command->shouldReceive('getOptions')->andReturn($options);
        $command->shouldReceive('getMessage')->andReturn($message);
        return $command;
    }

    /**
     * @param \MessageApp\ApplicationUser $user
     * @param string          $move
     * @return GameMoveCommand
     */
    public function getGameMoveCommand(ApplicationUser $user = null, $move = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\GameMoveCommand');
        $command->shouldReceive('getUser')->andReturn($user);
        $command->shouldReceive('getMove')->andReturn($move);
        return $command;
    }

    /**
     * @param \MessageApp\ApplicationUser $user
     * @return GameMoveCommand
     */
    public function getJoinGameCommand(ApplicationUser $user = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\JoinGameCommand');
        $command->shouldReceive('getUser')->andReturn($user);
        return $command;
    }
} 