<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Player;

class AbstractGameCommand implements GameCommand
{

    /**
     * @var Player
     */
    protected $player;

    /**
     * Constructor
     *
     * @param Player $player
     */
    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    /**
     * Returns the user
     *
     * @return Player
     */
    public function getPlayer()
    {
        return $this->player;
    }
} 