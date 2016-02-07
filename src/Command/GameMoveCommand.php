<?php
namespace MiniGameApp\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\Move;
use RemiSan\Command\Context;

class GameMoveCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.MOVE';

    /**
     * @var Move
     */
    private $move;

    /**
     * Constructor.
     */
    public function __construct()
    {
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

    /**
     * Static constructor.
     *
     * @param MiniGameId $gameId
     * @param PlayerId   $playerId
     * @param Move       $move
     * @param Context     $origin
     *
     * @return GameMoveCommand
     */
    public static function create(
        MiniGameId $gameId,
        PlayerId $playerId,
        Move $move,
        Context $origin = null
    ) {
        $obj = new self();

        $obj->init($gameId, $playerId, $origin);

        $obj->move = $move;

        return $obj;
    }
}
