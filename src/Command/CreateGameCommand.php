<?php

namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use RemiSan\Context\Context;

class CreateGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.CREATE';

    /**
     * @var GameOptions
     */
    private $options;

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
     * @return GameOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Construct
     *
     * @param MiniGameId  $id
     * @param PlayerId    $playerId
     * @param GameOptions $options
     * @param Context     $origin
     *
     * @return CreateGameCommand
     */
    public static function create(
        MiniGameId $id,
        PlayerId $playerId,
        GameOptions $options = null,
        Context $origin = null
    ) {
        $obj = new self();

        $obj->init($id, $playerId, $origin);
        $obj->options = $options;

        return $obj;
    }
}
