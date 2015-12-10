<?php
namespace MiniGameApp\Repository;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;

interface MiniGameRepository
{
    /**
     * Finds an minigame by its primary key / identifier.
     *
     * @param MiniGameId $id The identifier.
     *
     * @return object The minigame.
     */
    public function find($id);

    /**
     * Saves a mini game
     *
     * @param  MiniGame $game
     *
     * @return void
     */
    public function save(MiniGame $game);
}
