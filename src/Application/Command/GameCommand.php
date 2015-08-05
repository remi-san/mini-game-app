<?php
namespace MiniGameApp\Application\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;

interface GameCommand extends NamedCommand
{
    /**
     * Returns the player
     *
     * @return PlayerId
     */
    public function getPlayerId();

    /**
     * Returns the game
     *
     * @return MiniGameId
     */
    public function getGameId();
}
