<?php

namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use RemiSan\Context\Context;

class CreatePlayerCommand extends AbstractPlayerCommand
{
    const NAME = 'PLAYER.CREATE';

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
     * @return CreatePlayerCommand
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
