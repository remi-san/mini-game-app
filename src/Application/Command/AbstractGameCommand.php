<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Player;

abstract class AbstractGameCommand implements GameCommand
{

    /**
     * @var Player
     */
    protected $player;

    /**
     * @var string
     */
    protected $name;

    /**
     * Constructor
     *
     * @param Player $player
     * @param string $name
     */
    public function __construct(Player $player, $name = null)
    {
        $this->player = $player;
        $this->name = $name;
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

    /**
     * Returns the command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return $this->name;
    }
}
