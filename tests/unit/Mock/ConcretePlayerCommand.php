<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGameApp\Command\AbstractPlayerCommand;
use RemiSan\Context\Context;

class ConcretePlayerCommand extends AbstractPlayerCommand
{
    /**
     * Constructor.
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Context    $context
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        Context $context = null
    ) {
        $this->init($gameId, $playerId, $context);
    }

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
