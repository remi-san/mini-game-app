<?php
namespace MiniGameApp\Application\Command;

use MessageApp\Application\Command\AbstractApplicationCommand;
use MessageApp\Application\Command\ApplicationCommand;
use MessageApp\ApplicationUser;
use MiniGame\Move;

class GameMoveCommand extends AbstractApplicationCommand implements ApplicationCommand {

    /**
     * @var Move
     */
    protected $move;

    /**
     * Constructor
     *
     * @param \MessageApp\ApplicationUser $user
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