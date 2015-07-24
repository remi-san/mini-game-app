<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Move;
use MiniGame\Player;

class GameMoveCommand extends AbstractGameCommand
{
    const NAME = 'GAME.MOVE';

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
     * Returns the command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
    }

    /**
     * @return Move
     */
    public function getMove()
    {
        return $this->move;
    }
}
