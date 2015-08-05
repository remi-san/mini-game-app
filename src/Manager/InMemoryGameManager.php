<?php
namespace MiniGameApp\Manager;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

abstract class InMemoryGameManager implements GameManager, LoggerAwareInterface
{
    /**
     * @var \MiniGame\Entity\MiniGame[]
     */
    protected $managedMiniGames;

    /**
     * @var array
     */
    protected $playersMiniGames;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param \MiniGame\Entity\MiniGame[] $managedMiniGames
     * @param array      $playersMiniGames
     */
    public function __construct(array $managedMiniGames = array(), array $playersMiniGames = array())
    {
        $this->managedMiniGames = $managedMiniGames;
        $this->playersMiniGames = $playersMiniGames;
        $this->logger = new NullLogger();
    }

    /**
     * Create a mini-game according to the options
     *
     * @param  GameOptions $options
     * @return \MiniGame\Entity\MiniGame
     */
    abstract public function createMiniGame(GameOptions $options);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return \MiniGame\Entity\MiniGame
     */
    public function saveMiniGame(MiniGame $game)
    {
        $this->managedMiniGames[(string)$game->getId()] = $game;

        foreach ($game->getPlayers() as $player) {
            $this->playersMiniGames[(string)$player->getId()] = $game;
        }

        return $game;
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
        if (!array_key_exists((string)$id, $this->managedMiniGames)) {
            throw new GameNotFoundException('Game with id "' . $id . '" doesn\'t exist!');
        }

        return $this->managedMiniGames[(string)$id];
    }

    /**
     * Get the active mini-game for the player
     *
     * @param PlayerId $player
     * @return \MiniGame\Entity\MiniGame
     * @throws GameNotFoundException
     */
    public function getActiveMiniGameForPlayer(PlayerId $player)
    {
        if (!array_key_exists((string)$player, $this->playersMiniGames)) {
            throw new GameNotFoundException('Game with for user "' . $player . '" doesn\'t exist!');
        }

        return $this->playersMiniGames[(string)$player];
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
        if (!array_key_exists((string)$id, $this->managedMiniGames)) {
            throw new GameNotFoundException('Game with id "' . $id . '" doesn\'t exist!');
        }

        $miniGame = $this->managedMiniGames[(string)$id];
        foreach ($miniGame->getPlayers() as $player) {
            unset($this->playersMiniGames[(string)$player->getId()]);
        }

        unset($this->managedMiniGames[(string)$id]);
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
