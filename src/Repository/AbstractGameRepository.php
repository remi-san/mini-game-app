<?php
namespace MiniGameApp\Repository;

use Doctrine\ORM\ORMException;
use League\Event\EmitterInterface;
use League\Event\EventInterface;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Exception\GameNotFoundException;
use MiniGameApp\Store\MiniGameStore;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class AbstractGameRepository implements GameRepository, LoggerAwareInterface
{
    /**
     * @var MiniGameStore
     */
    private $gameStore;

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
     * @param MiniGameStore      $gameStore
     * @param EmitterInterface   $eventEmitter
     */
    public function __construct(
        MiniGameStore $gameStore,
        EmitterInterface $eventEmitter
    ) {
        $this->gameStore = $gameStore;
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
        $eventStream = $this->gameStore->save($game);

        foreach ($eventStream as $domainMessage) {
            $event = $this->prepareEvent($domainMessage);
            $this->logger->debug('Domain event to dispatch', array('name' => $event->getName()));
            $this->eventEmitter->emit($event);
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
            $game = $this->gameStore->find($id);
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
