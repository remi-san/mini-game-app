<?php
namespace MiniGameApp\Manager;

use MessageApp\ApplicationUser;
use MiniGame\MiniGamePlayer;
use MiniGame\Player;

abstract class InMemoryPlayerManager implements PlayerManager {

    /**
     * @var Player[]
     */
    protected $players;

    /**
     * Constructor
     *
     * @param Player[] $players
     */
    public function __construct(array $players = array())
    {
        $this->players = $players;
    }

    /**
     * Gets the player matching the twitter user
     * If the player doesn't exist yet, it creates him
     *
     * @param  \MessageApp\ApplicationUser $user
     * @return Player
     */
    public function getPlayer(ApplicationUser $user)
    {
        $userId = $user->getId();
        if (!array_key_exists($userId, $this->players)) {
            $this->savePlayer($this->createPlayer($user));
        }
        return $this->players[$userId];
    }

    /**
     * create a player
     *
     * @param  \MessageApp\ApplicationUser $user
     * @return Player
     */
    public function createPlayer(ApplicationUser $user) {
        return new MiniGamePlayer($user->getId(), $user->getName());
    }

    /**
     * Saves a player
     *
     * @param Player $player
     */
    public function savePlayer(Player $player)
    {
        $this->players[$player->getId()] = $player;
    }
} 