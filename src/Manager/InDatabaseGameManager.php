<?php
namespace MiniGameApp\Manager;

use Broadway\EventHandling\EventBusInterface;
use Doctrine\ORM\ORMException;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGame\Repository\MiniGameRepository;
use MiniGame\Repository\PlayerRepository;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;

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
     * @var EventBusInterface
     */
    private $eventBus;

    /**
     * Constructor
     *
     * @param MiniGameRepository $gameRepository
     * @param PlayerRepository   $playerRepository
     * @param EventBusInterface  $eventBus
     */
    public function __construct(
        MiniGameRepository $gameRepository,
        PlayerRepository $playerRepository,
        EventBusInterface $eventBus
    ) {
        $this->gameRepository = $gameRepository;
        $this->playerRepository = $playerRepository;
        $this->eventBus = $eventBus;
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

        $this->eventBus->publish($game->getUncommittedEvents());
    }

    /**
     * Get the active mini-game for the player
     *
     * @param  PlayerId $player
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getActiveMiniGameForPlayer(PlayerId $player)
    {
        $game = null;
        try {
            $game = $this->gameRepository->findPlayerMinigame($player);
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
     * @param  MiniGameId $id
     * @return void
     * @throws GameNotFoundException
     */
    public function deleteMiniGame(MiniGameId $id)
    {
        $game = $this->getMiniGame($id);
        $players = $game->getPlayers();

        foreach ($players as $player) {
            $this->playerRepository->delete($player);
        }

        $this->gameRepository->delete($game);
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
