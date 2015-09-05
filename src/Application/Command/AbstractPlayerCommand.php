<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;

abstract class AbstractPlayerCommand extends AbstractGameCommand
{
    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     */
    public function __construct(MiniGameId $gameId, PlayerId $playerId)
    {
        parent::__construct($gameId);
        $this->playerId = $playerId;
    }

    /**
     * Returns the player id
     *
     * @return PlayerId
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }
}
