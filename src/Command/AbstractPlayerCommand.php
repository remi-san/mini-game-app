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
    protected $gameId;

    /**
     * @var PlayerId
     */
    protected $playerId;

    /**
     * @var Context
     */
    protected $context;

    /**
     * Init.
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Context    $context
     */
    protected function init(
        MiniGameId $gameId,
        PlayerId $playerId,
        Context $context = null
    ) {
        $this->gameId = $gameId;
        $this->playerId = $playerId;
        $this->context = $context;
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
