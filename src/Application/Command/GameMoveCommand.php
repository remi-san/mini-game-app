<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Move;
use MiniGame\Player;

class GameMoveCommand extends AbstractGameCommand {

    /**
     * @var Move
     */
    protected $move;

    /**
     * Constructor
     *
     * @param Player $player
     * @param Move   $move
     */
    public function __construct(Player $player, Move $move)
    {
        $this->move = $move;
        parent::__construct($player);
    }

    /**
     * @return Move
     */
    public function getMove()
    {
        return $this->move;
    }
}