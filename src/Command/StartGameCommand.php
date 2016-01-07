<?php
namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\PlayerOptions;
use TwitterHangman\Context\Context;

class StartGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.START';

    /**
     * Constructor
     *
     * @param MiniGameId    $gameId
     * @param PlayerId      $playerId
     * @param Context       $context
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        Context $context = null
    ) {
        parent::__construct($gameId, $playerId, $context);
    }

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
