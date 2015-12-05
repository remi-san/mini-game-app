<?php
namespace MiniGameApp\Event;

use League\Event\Event;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameResult;

class MiniGameAppErrorEvent extends Event implements GameResult
{
    /**
     * @var MiniGameId
     */
    private $gameId;

    /**
     * @var PlayerId
     */
    private $playerId;

    /**
     * @var string
     */
    private $message;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param string     $message
     */
    public function __construct(MiniGameId $gameId, PlayerId $playerId, $message)
    {
        parent::__construct('minigame.error');
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->message = $message;
    }

    /**
     * @return MiniGameId
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return PlayerId
     */
    public function getPlayerId()
    {
        return $this->playerId;
    }

    /**
     * @return string
     */
    public function getAsMessage()
    {
        return $this->message;
    }
}
