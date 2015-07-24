<?php
namespace MiniGameApp\Application\Command;

use MiniGame\GameOptions;
use MiniGame\Player;

class LeaveGameCommand extends AbstractGameCommand
{
    const NAME = 'GAME.LEAVE';

    /**
     * @var string
     */
    protected $gameId;

    /**
     * Construct
     *
     * @param Player $player
     * @param string $gameId
     */
    public function __construct(Player $player, $gameId)
    {
        $this->gameId = $gameId;
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
}
