<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;

abstract class AbstractPlayerCommand implements NamedCommand
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
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     */
    public function __construct(MiniGameId $gameId, PlayerId $playerId)
    {
        $this->gameId = $gameId;
        $this->playerId = $playerId;
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
}
