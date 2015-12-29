<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use RemiSan\Command\Context;
use RemiSan\Command\ContextAwareCommand;

abstract class AbstractPlayerCommand implements NamedCommand, ContextAwareCommand
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
     * @var Context
     */
    private $context;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Context    $context
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        Context $context = null
    ) {
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->origin = $context;
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
     * Returns the context
     *
     * @return Context
     */
    public function getContext()
    {
        return $this->context;
    }
}
