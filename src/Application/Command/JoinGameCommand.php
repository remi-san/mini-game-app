<?php
namespace MiniGameApp\Application\Command;

use MiniGame\GameOptions;
use MiniGame\Player;

class JoinGameCommand extends AbstractGameCommand
{
    const NAME = 'GAME.JOIN';

    /**
     * @var string
     */
    protected $gameId;

    /**
     * @var string
     */
    protected $message;

    /**
     * Construct
     *
     * @param Player $player
     * @param string $gameId
     * @param string $message
     */
    public function __construct(Player $player, $gameId, $message)
    {
        $this->gameId = $gameId;
        $this->message = $message;
        parent::__construct($player);
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
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
}
