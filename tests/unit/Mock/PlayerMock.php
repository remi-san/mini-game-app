<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;

class PlayerMock implements Player
{
    /**
     * @var MiniGame
     */
    private $game;

    public function getId()
    {
        return new PlayerId(1);
    }

    public function getName()
    {
        return 'John';
    }

    /**
     * Returns the game
     *
     * @return MiniGame
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Sets the game
     *
     * @param  MiniGame $game
     * @return void
     */
    public function setGame(MiniGame $game)
    {
        $this->game = $game;
    }

    /**
     * Returns the external reference
     *
     * @return string
     */
    public function getExternalReference()
    {
        return 'ext';
    }

    /**
     * Has player lost?
     *
     * @return bool
     */
    public function hasLost()
    {
        return false;
    }

    /**
     * Has player won?
     *
     * @return bool
     */
    public function hasWon()
    {
        return true;
    }

    /**
     * @param  Player $player
     * @return mixed
     */
    public function equals(Player $player)
    {
        return $player->getId()->equals($this->getId());
    }
}
