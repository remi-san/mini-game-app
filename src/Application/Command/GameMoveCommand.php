<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Move;
use MiniGameApp\ApplicationUser;

class GameMoveCommand extends AbstractApplicationCommand implements ApplicationCommand {

    /**
     * @var Move
     */
    protected $move;

    /**
     * Constructor
     *
     * @param \MiniGameApp\ApplicationUser $user
     * @param Move            $move
     */
    public function __construct(ApplicationUser $user, Move $move)
    {
        $this->move = $move;
        parent::__construct($user);
    }

    /**
     * @return Move
     */
    public function getMove()
    {
        return $this->move;
    }
}