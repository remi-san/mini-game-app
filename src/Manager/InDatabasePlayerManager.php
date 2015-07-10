<?php
namespace MiniGameApp\Manager;

use Doctrine\ORM\ORMException;
use MiniGame\Player;
use MiniGame\Repository\PlayerRepository;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\Exceptions\PlayerNotFoundException;
use MiniGameApp\Manager\Exceptions\UnbuildablePlayerException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class InDatabasePlayerManager implements PlayerManager, LoggerAwareInterface
{
    /**
     * @var PlayerRepository
     */
    protected $repository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param PlayerRepository $repository
     */
    public function __construct(PlayerRepository $repository)
    {
        $this->repository = $repository;
        $this->logger = new NullLogger();
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
        $player = null;
        try {
            $player = $this->repository->find($id);
        } catch (ORMException $e) {}

        if (!$player) {
            throw new PlayerNotFoundException('Player with id "' . $id . '" doesn\'t exist!');
        }
        return $player;
    }

    /**
     * Retrieves a player
     *
     * @param  object $object
     * @throws PlayerException
     * @return Player
     */
    public abstract function getByObject($object);

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
        $this->repository->save($player);
    }

    /**
     * Can the player manager deal with that object?
     *
     * @param  object $object
     * @return boolean
     */
    protected abstract function supports($object);

    /**
     * @param  LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
} 