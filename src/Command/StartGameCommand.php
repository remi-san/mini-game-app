<?php

namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use TwitterHangman\Context\Context;

class StartGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.START';

    /**
     * Construct.
     */
    public function __construct()
    {
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

    /**
     * Construct
     *
     * @param MiniGameId  $id
     * @param PlayerId    $playerId
     * @param Context      $origin
     *
     * @return StartGameCommand
     */
    public static function create(
        MiniGameId $id,
        PlayerId $playerId,
        Context $origin = null
    ) {
        $obj = new self();

        $obj->init($id, $playerId, $origin);

        return $obj;
    }
}
