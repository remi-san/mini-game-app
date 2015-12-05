<?php
namespace MiniGameApp\Command;

class LeaveGameCommand extends AbstractPlayerCommand
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
