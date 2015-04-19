<?php
namespace MiniGameApp\Manager;

use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;

abstract class InMemoryGameManager implements GameManager {

    /**
     * @var MiniGame[]
     */
    protected $managedMiniGames;

    /**
     * @var array
     */
    protected $playersMiniGames;

    /**
     * Constructor
     *
     * @param MiniGame[] $managedMiniGames
     * @param array      $playersMiniGames
     */
    public function __construct(array $managedMiniGames = array(), array $playersMiniGames = array())
    {
        $this->managedMiniGames = $managedMiniGames;
        $this->playersMiniGames = $playersMiniGames;
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
        $this->managedMiniGames[$game->getId()] = $game;

        foreach ($game->getPlayers() as $player) {
            $this->playersMiniGames[$player->getId()] = $game;
        }

        return $game;
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
        if (!array_key_exists($id, $this->managedMiniGames)) {
            throw new GameNotFoundException('Game with id "'.$id.'" doesn\'t exist!');
        }

        return $this->managedMiniGames[$id];
    }

    /**
     * Get the active mini-game for the player
     *
     * @param Player $player
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getActiveMiniGameForPlayer(Player $player)
    {
        if (!array_key_exists($player->getId(), $this->playersMiniGames)) {
            throw new GameNotFoundException('Game with for user "' . $player->getId() . '" doesn\'t exist!');
        }

        return $this->playersMiniGames[$player->getId()];
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
        if (!array_key_exists($id, $this->managedMiniGames)) {
            throw new GameNotFoundException('Game with id "'.$id.'" doesn\'t exist!');
        }

        $miniGame = $this->managedMiniGames[$id];
        foreach($miniGame->getPlayers() as $player) {
            unset($this->playersMiniGames[$player->getId()]);
        }

        unset($this->managedMiniGames[$id]);
    }
} 