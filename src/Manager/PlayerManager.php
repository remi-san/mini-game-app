<?php
namespace MiniGameApp\Manager;

use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;

interface PlayerManager {

    /**
     * Retrieves a player
     *
     * @param  int $id
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function get($id);

    /**
     * Creates a player
     *
     * @param  object $object
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function create($object);

    /**
     * Saves a player
     *
     * @param  Player $player
     * @return void
     */
    public function save(Player $player);
} 