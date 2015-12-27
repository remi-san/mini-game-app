<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use RemiSan\Command\Origin;
use RemiSan\Command\OriginAwareCommand;

abstract class AbstractPlayerCommand implements NamedCommand, OriginAwareCommand
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
     * @var Origin
     */
    private $origin;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Origin     $origin
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        Origin $origin = null
    ) {
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->origin = $origin;
    }

    /**
     * Returns the minigame id
     *
     * @return MiniGameId
     */
    public function getGameId()
    {
        return $this->gameId;
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

    /**
     * Returns the command name
     *
     * @return string
     */
    abstract public function getCommandName();

    /**
     * Returns the origin
     *
     * @return Origin
     */
    public function getOrigin()
    {
        return $this->origin;
    }
}
