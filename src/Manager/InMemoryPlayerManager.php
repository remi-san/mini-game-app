<?php
namespace MiniGameApp\Manager;

use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;
use MiniGameApp\Manager\Exceptions\UnbuildablePlayerException;

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
        if (!array_key_exists($id, $this->players)) {
            throw new PlayerNotFoundException();
        }

        return $this->players[$id];
    }

    /**
     * Gets the user id from the user object
     *
     * @param  object $object
     * @throws PlayerException
     * @return string
     */
    protected abstract function getUserId($object);

    /**
     * Retrieves a player
     *
     * @param  object $object
     * @throws PlayerException
     * @return Player
     */
    public function getByObject($object)
    {
        if (!$this->supports($object)) {
            throw new UnbuildablePlayerException(); // TODO: change?
        }

        $userId = $this->getUserId($object);
        return $this->get($userId);
    }

    /**
     * Creates a player
     *
     * @param  object $object
     * @throws UnbuildablePlayerException
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