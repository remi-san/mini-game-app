<?php
namespace MiniGameApp\Manager;

use Doctrine\ORM\ORMException;
use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;
use MiniGame\Repository\MiniGameRepository;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class InDatabaseGameManager implements GameManager, LoggerAwareInterface
{
    /**
     * @var MiniGameRepository
     */
    protected $repository;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param MiniGameRepository $repository
     */
    public function __construct(MiniGameRepository $repository)
    {
        $this->repository = $repository;
        $this->logger = new NullLogger();
    }

    /**
     * Create a mini-game according to the options
     *
     * @param  GameOptions $options
     * @return MiniGame
     */
    abstract public function createMiniGame(GameOptions $options);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function saveMiniGame(MiniGame $game)
    {
        $this->repository->save($game);
    }

    /**
     * Get the active mini-game for the player
     *
     * @param  Player $player
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getActiveMiniGameForPlayer(Player $player)
    {
        $game = null;
        try {
            $game = $this->repository->findPlayerMinigame($player);
        } catch (ORMException $e) {
        }

        if (!$game) {
            throw new GameNotFoundException('Game with for user "' . $player->getId() . '" doesn\'t exist!');
        }
        return $game;
    }

    /**
     * Delete the mini-game corresponding to the id
     *
     * @param  string $id
     * @return void
     * @throws GameNotFoundException
     */
    public function deleteMiniGame($id)
    {
        $game = $this->getMiniGame($id);
        $this->repository->delete($game);
    }

    /**
     * Get the mini-game corresponding to the id
     *
     * @param  string $id
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getMiniGame($id)
    {
        $game = null;
        try {
            $game = $this->repository->find($id);
        } catch (ORMException $e) {
        }

        if (!$game) {
            throw new GameNotFoundException('Game with id "' . $id . '" doesn\'t exist!');
        }
        return $game;
    }

    /**
     * @param  LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
} 