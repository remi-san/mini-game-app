<?php
namespace MiniGameApp\Manager;

use MiniGame\Player;
use MiniGameApp\ApplicationUser;

interface PlayerManager {

    /**
     * Gets the player matching the twitter user
     * If the player doesn't exist yet, it creates him
     *
     * @param ApplicationUser $user
     * @return Player
     */
    public function getPlayer(ApplicationUser $user);

    /**
     * create a player
     *
     * @param  ApplicationUser $user
     * @return Player
     */
    public function createPlayer(ApplicationUser $user);

    /**
     * Saves a player
     *
     * @param Player $player
     */
    public function savePlayer(Player $player);
} 