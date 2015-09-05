<?php
namespace MiniGameApp\Test\Mock;

use MiniGameApp\Application\Command\AbstractPlayerCommand;

class ConcretePlayerCommand extends AbstractPlayerCommand
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
