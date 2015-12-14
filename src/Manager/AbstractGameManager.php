<?php
namespace MiniGameApp\Manager;

use Doctrine\ORM\ORMException;
use League\Event\EmitterInterface;
use League\Event\EventInterface;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Store\MiniGameStore;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractGameManager implements GameManager, LoggerAwareInterface
{
    /**
     * @var \MiniGameApp\Store\MiniGameStore
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
     * @param \MiniGameApp\Store\MiniGameStore $gameRepository
     * @param EmitterInterface   $eventEmitter
     */
    public function __construct(
        MiniGameStore $gameRepository,
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
            $this->eventEmitter->emit($this->prepareEvent($domainMessage));
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
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Prepares the event to return a League Event
     *
     * @param  mixed $originalEvent
     * @return EventInterface
     */
    abstract protected function prepareEvent($originalEvent);
}
