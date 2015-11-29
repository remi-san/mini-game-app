<?php
namespace MiniGameApp\Manager;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;

interface GameManager
{
    /**
     * Create a mini-game according to the options
     *
     * @param  MiniGameId  $id
     * @param  PlayerId    $playerId
     * @param  GameOptions $options
     * @return MiniGame
     */
    public function createMiniGame(MiniGameId $id, PlayerId $playerId, GameOptions $options);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function saveMiniGame(MiniGame $game);

    /**
     * Get the mini-game corresponding to the id
     *
     * @param  MiniGameId $id
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getMiniGame(MiniGameId $id);
}
