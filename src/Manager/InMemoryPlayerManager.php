<?php
namespace MiniGameApp\Manager;

use MessageApp\User\Exception\AppUserException;
use MessageApp\User\Exception\UnsupportedUserException;
use MessageApp\User\UndefinedApplicationUser;
use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;

abstract class InMemoryPlayerManager implements PlayerManager {

    /**
     * @var Player[]
     */
    protected $players;

    /**
     * Constructor
     *
     * @param Player[] $players
     */
    public function __construct(array $players = array())
    {
        $this->players = $players;
    }

    /**
     * Retrieves a player
     *
     * @param  int $id
     * @return Player
     * @throws PlayerNotFoundException
     */
    public function get($id)
    {
        return $this->players[$id];
    }

    /**
     * Gets the user id from the user object
     *
     * @param  object $object
     * @throws AppUserException
     * @return string
     */
    protected abstract function getUserId($object);

    /**
     * Retrieves a player
     *
     * @param  object $object
     * @throws UnsupportedUserException
     * @return Player
     */
    public function getByObject($object)
    {
        if (!$this->supports($object)) {
            throw new UnsupportedUserException(new UndefinedApplicationUser($object)); // TODO: change?
        }

        $userId = $this->getUserId($object);
        if (!array_key_exists($userId, $this->players)) {
            $this->save($this->create($object));
        }
        return $this->players[$userId];
    }

    /**
     * Creates a player
     *
     * @param  object $object
     * @throws AppUserException
     * @return Player
     */
    public abstract function create($object);

    /**
     * Saves a player
     *
     * @param  Player $player
     * @return void
     */
    public function save(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }

    /**
     * Can the player manager deal with that object?
     *
     * @param  object $object
     * @return boolean
     */
    protected abstract function supports($object);
} 