<?php
namespace MiniGameApp\Manager;

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
     * Creates a player
     *
     * @param  object $object
     * @return Player
     * @throws PlayerNotFoundException
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
} 