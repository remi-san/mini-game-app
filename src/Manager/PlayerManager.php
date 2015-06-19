<?php
namespace MiniGameApp\Manager;

use MessageApp\User\Exception\AppUserException;
use MessageApp\User\Exception\UnsupportedUserException;
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
     * Retrieves a player
     *
     * @param  object $object
     * @throws UnsupportedUserException
     * @return Player
     */
    public function getByObject($object);

    /**
     * Creates a player
     *
     * @param  object $object
     * @return Player
     * @throws AppUserException
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