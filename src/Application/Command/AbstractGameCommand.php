<?php
namespace MiniGameApp\Application\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;

abstract class AbstractGameCommand implements NamedCommand
{
    /**
     * @var MiniGameId
     */
    private $gameId;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     */
    public function __construct(MiniGameId $gameId)
    {
        $this->gameId = $gameId;
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
     * Returns the command name
     *
     * @return string
     */
    abstract public function getCommandName();
}
