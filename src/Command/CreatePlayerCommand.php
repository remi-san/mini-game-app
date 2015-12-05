<?php
namespace MiniGameApp\Command;

class CreatePlayerCommand extends AbstractPlayerCommand
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
