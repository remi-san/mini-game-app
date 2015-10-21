<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\Player;

class PlayerMock implements Player
{
    /**
     * @var MiniGame
     */
    private $game;

    public function getId()
    {
        return new MiniGameId(1);
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
}
