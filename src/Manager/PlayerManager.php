<?php
namespace MiniGameApp\Manager;

use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;
use MiniGameApp\Manager\Exceptions\UnbuildablePlayerException;

interface PlayerManager
{
    /**
     * Retrieves a player
     *
     * @param  int $id
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function get($id);

    /**
     * Retrieves a player
     *
     * @param  object $object
     * @throws PlayerException
     * @return Player
     */
    public function getByObject($object);

    /**
     * Creates a player
     *
     * @param  object $object
     * @return Player
     * @throws UnbuildablePlayerException
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
