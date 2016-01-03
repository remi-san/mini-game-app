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
     * Constructor
     *
     * @param MiniGameId    $gameId
     * @param PlayerId      $playerId
     * @param PlayerOptions $playerOptions
     * @param Context       $context
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        PlayerOptions $playerOptions,
        Context $context = null
    ) {
        $this->playerOptions = $playerOptions;
        parent::__construct($gameId, $playerId, $context);
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
}
