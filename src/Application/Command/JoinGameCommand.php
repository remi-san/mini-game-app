<?php
namespace MiniGameApp\Application\Command;

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
