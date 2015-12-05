<?php
namespace MiniGameApp\Command;

class JoinGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.JOIN';

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
