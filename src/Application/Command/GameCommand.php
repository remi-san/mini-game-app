<?php
namespace MiniGameApp\Application\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Player;

interface GameCommand extends NamedCommand
{
    /**
     * Returns the player
     *
     * @return Player
     */
    public function getPlayer();
}
