<?php
namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Move;
use RemiSan\Command\Origin;

class GameMoveCommand extends AbstractPlayerCommand
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
     * @param Origin     $origin
     */
    public function __construct(
        MiniGameId $gameId,
        PlayerId $playerId,
        Move $move,
        Origin $origin = null
    ) {
        $this->move = $move;
        parent::__construct($gameId, $playerId, $origin);
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
