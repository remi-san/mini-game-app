<?php
namespace MiniGameApp\Application\Command;

class LeaveGameCommand extends AbstractGameCommand
{
    const NAME = 'GAME.LEAVE';

    /**
     * Returns the command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }
}
