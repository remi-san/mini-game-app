<?php
namespace MiniGameApp\Application\Command;

class JoinGameCommand extends AbstractGameCommand
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
