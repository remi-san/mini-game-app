<?php
namespace MiniGameApp\Manager;

use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;
use MiniGameApp\Manager\Exceptions\UnbuildablePlayerException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class InMemoryPlayerManager implements PlayerManager, LoggerAwareInterface
{

    /**
     * @var Player[]
     */
    protected $players;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param Player[] $players
     */
    public function __construct(array $players = array())
    {
        $this->players = $players;
        $this->logger = new NullLogger();
    }

    /**
     * Retrieves a player
     *
     * @param  object $object
     * @throws PlayerException
     * @return Player
     */
    public function getByObject($object)
    {
        $userId = $this->getUserId($object);
        return $this->get($userId);
    }

    /**
     * Gets the user id from the user object
     *
     * @param  object $object
     * @throws PlayerException
     * @return string
     */
    abstract protected function getUserId($object);

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
     * Creates a player
     *
     * @param  object $object
     * @throws UnbuildablePlayerException
     * @return Player
     */
    abstract public function create($object);

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
     * @param  LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Can the player manager deal with that object?
     *
     * @param  object $object
     * @return boolean
     */
    abstract protected function supports($object);
}
