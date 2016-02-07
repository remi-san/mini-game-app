<?php
namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\PlayerOptions;
use TwitterHangman\Context\Context;

class JoinGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.JOIN';

    /**
     * @var PlayerOptions
     */
    private $playerOptions;

    /**
     * Constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return PlayerOptions
     */
    public function getPlayerOptions()
    {
        return $this->playerOptions;
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
     * Static constructor.
     *
     * @param MiniGameId    $gameId
     * @param PlayerId      $playerId
     * @param PlayerOptions $playerOptions
     * @param Context       $context
     *
     * @return JoinGameCommand
     */
    public static function create(
        MiniGameId $gameId,
        PlayerId $playerId,
        PlayerOptions $playerOptions,
        Context $context = null
    ) {
        $obj = new self();

        $obj->init($gameId, $playerId, $context);

        $obj->playerOptions = $playerOptions;

        return $obj;
    }
}
