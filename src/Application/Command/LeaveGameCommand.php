<?php
namespace MiniGameApp\Application\Command;

use MiniGame\GameOptions;
use MiniGame\Player;

class LeaveGameCommand extends AbstractGameCommand
{

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
     * @return GameOptions
     */
    public function getGameId()
    {
        return $this->gameId;
    }
}
