<?php
namespace MiniGameApp\ReadModel;

use MiniGame\Entity\PlayerId;

interface Player
{
    /**
     * @return PlayerId
     */
    public function getId();

    /**
     * @return MiniGame
     */
    public function getGame();
}
