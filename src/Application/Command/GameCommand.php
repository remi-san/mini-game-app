<?php
namespace MiniGameApp\Application\Command;

use Command\Command;
use MiniGame\Player;

interface GameCommand extends Command
{

    /**
     * Returns the player
     *
     * @return Player
     */
    public function getPlayer();
} 