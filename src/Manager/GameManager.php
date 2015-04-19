<?php
namespace MiniGameApp\Manager;

use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;

interface GameManager {

    /**
     * Create a mini-game according to the options
     *
     * @param  GameOptions $options
     * @return MiniGame
     */
    public function createMiniGame(GameOptions $options);

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function saveMiniGame(MiniGame $game);

    /**
     * Get the mini-game corresponding to the id
     *
     * @param  string $id
     * @return MiniGame
     */
    public function getMiniGame($id);

    /**
     * Get the active mini-game for the player
     *
     * @param Player $player
     * @return MiniGame
     */
    public function getActiveMiniGameForPlayer(Player $player);

    /**
     * Delete the mini-game corresponding to the id
     *
     * @param  string $id
     * @return void
     */
    public function deleteMiniGame($id);
} 