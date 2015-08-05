<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Move;

class GameMoveCommand extends AbstractGameCommand
{
    const NAME = 'GAME.MOVE';

    /**
     * @var Move
     */
    private $move;

    /**
     * Constructor
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Move       $move
     */
    public function __construct(MiniGameId $gameId, PlayerId $playerId, Move $move)
    {
        $this->move = $move;
        parent::__construct($gameId, $playerId);
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
