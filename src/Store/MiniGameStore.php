<?php
namespace MiniGameApp\Store;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;

interface MiniGameStore
{
    /**
     * Finds an minigame by its primary key / identifier.
     *
     * @param  MiniGameId $id The identifier.
     *
     * @return MiniGame The minigame.
     */
    public function find($id);

    /**
     * Saves a mini game
     *
     * @param  MiniGame $game
     *
     * @return array
     */
    public function save(MiniGame $game);
}
