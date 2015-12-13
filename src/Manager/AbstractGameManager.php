<?php
namespace MiniGameApp\Manager;

use Broadway\Domain\DomainMessage;
use Doctrine\ORM\ORMException;
use League\Event\EmitterInterface;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Repository\MiniGameRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractGameManager implements GameManager, LoggerAwareInterface
{
    /**
     * @var MiniGameRepository
     */
    private $gameRepository;

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Constructor
     *
     * @param MiniGameRepository $gameRepository
     * @param EmitterInterface   $eventEmitter
     */
    public function __construct(
        MiniGameRepository $gameRepository,
        EmitterInterface $eventEmitter
    ) {
        $this->gameRepository = $gameRepository;
        $this->eventEmitter = $eventEmitter;
        $this->logger = new NullLogger();
    }

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function saveMiniGame(MiniGame $game)
    {
        $this->gameRepository->save($game);

        $eventStream = $game->getUncommittedEvents();
        foreach ($eventStream as $domainMessage) {
            /* @var $domainMessage DomainMessage */
            $this->eventEmitter->emit($domainMessage->getPayload());
        }
    }

    /**
     * Get the mini-game corresponding to the id
     *
     * @param  MiniGameId $id
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getMiniGame(MiniGameId $id)
    {
        $game = null;
        try {
            $game = $this->gameRepository->find($id);
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
