<?php
namespace MiniGameApp\Repository;

use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;

interface PlayerRepository
{
    /**
     * Finds an player by its primary key / identifier.
     *
     * @param PlayerId $id The identifier.
     *
     * @return Player The player.
     */
    public function find($id);

    /**
     * Saves a player
     *
     * @param  Player $player
     *
     * @return void
     */
    public function save(Player $player);
}
