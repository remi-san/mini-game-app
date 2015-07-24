<?php
namespace MiniGameApp\Test\Mock;

use MiniGameApp\Application\Command\AbstractGameCommand;

class ConcreteGameCommand extends AbstractGameCommand
{
    /**
     * Returns the command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return 'TEST';
    }

}
