<?php
namespace MiniGameApp\Application\Command;

class CreatePlayerCommand extends AbstractGameCommand
{
    const NAME = 'PLAYER.CREATE';

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
