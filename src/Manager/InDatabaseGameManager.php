<?php
namespace MiniGameApp\Manager;

use Broadway\Domain\DomainMessage;
use Doctrine\ORM\ORMException;
use League\Event\EmitterInterface;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Repository\MiniGameRepository;
use MiniGameApp\Repository\PlayerRepository;

abstract class InDatabaseGameManager implements GameManager
{
    /**
     * @var MiniGameRepository
     */
    private $gameRepository;

    /**
     * @var PlayerRepository
     */
    private $playerRepository;

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /**
     * Constructor
     *
     * @param MiniGameRepository $gameRepository
     * @param PlayerRepository   $playerRepository
     * @param EmitterInterface   $eventEmitter
     */
    public function __construct(
        MiniGameRepository $gameRepository,
        PlayerRepository $playerRepository,
        EmitterInterface $eventEmitter
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->eventEmitter = $eventEmitter;
    }

    /**
     * Create a mini-game according to the options
     *
     * @param  MiniGameId  $id
     * @param  GameOptions $options
     * @return MiniGame
     */
    abstract public function createMiniGame(MiniGameId $id, GameOptions $options);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function saveMiniGame(MiniGame $game)
    {
        $this->gameRepository->save($game);
        $players = $game->getPlayers();

        foreach ($players as $player) {
            $this->playerRepository->save($player);
        }

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
}
