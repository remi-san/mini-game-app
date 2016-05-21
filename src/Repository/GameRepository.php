<?php

namespace MiniGameApp\Repository;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Exception\GameNotFoundException;

interface GameRepository
{
    /**
     * Get the mini-game corresponding to the id
     *
     * @param  MiniGameId $id
     * @throws GameNotFoundException
     * @return MiniGame
     */
    public function load(MiniGameId $id);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function save(MiniGame $game);
}
