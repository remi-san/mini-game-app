<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\CommandExecutor;
use MiniGameApp\Application\Response\ApplicationResponse;
use MiniGameApp\Application\Response\SendMessageResponse;
use MiniGameApp\ApplicationUser;
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
     * @param ApplicationUser $user
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
     * @param ApplicationUser $user
     * @return GameMoveCommand
     */
    public function getJoinGameCommand(ApplicationUser $user = null)
    {
        $command = \Mockery::mock('\\MiniGameApp\\Application\\Command\\JoinGameCommand');
        $command->shouldReceive('getUser')->andReturn($user);
        return $command;
    }

    /**
     * @param  ApplicationResponse $response
     * @return CommandExecutor
     */
    public function getExecutor(ApplicationResponse $response = null) {
        $executor = \Mockery::mock('\\MiniGameApp\\Application\\CommandExecutor');
        if ($response) {
            $executor->shouldReceive('execute')->andReturn($response);
        }
        return $executor;
    }

    /**
     * @param  ApplicationUser $user
     * @param  string $message
     * @return SendMessageResponse
     */
    public function getSendMessageResponse(ApplicationUser $user = null, $message = null) {
        $response = \Mockery::mock('\\MiniGameApp\\Application\\Response\\SendMessageResponse');
        $response->shouldReceive('getUser')->andReturn($user);
        $response->shouldReceive('getMessage')->andReturn($message);
        return $response;
    }

    /**
     * @param  int    $id
     * @param  string $name
     * @return ApplicationUser
     */
    public function getApplicationUser($id, $name)
    {
        $appUser = \Mockery::mock('\\MiniGameApp\\ApplicationUser');
        $appUser->shouldReceive('getId')->andReturn($id);
        $appUser->shouldReceive('getName')->andReturn($name);
        return $appUser;
    }
} 