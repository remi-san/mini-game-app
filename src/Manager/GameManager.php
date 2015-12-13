<?php
namespace MiniGameApp\Manager;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;

interface GameManager
{
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
