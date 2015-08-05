<?php
namespace MiniGameApp\Manager;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;

interface GameManager
{
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
     * @param  MiniGameId $id
     * @return MiniGame
     * @throws GameNotFoundException
     */
    public function getMiniGame(MiniGameId $id);

    /**
     * Delete the mini-game corresponding to the id
     *
     * @param  MiniGameId $id
     * @return void
     * @throws GameNotFoundException
     */
    public function deleteMiniGame(MiniGameId $id);
}
