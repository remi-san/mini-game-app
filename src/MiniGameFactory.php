<?php

namespace MiniGameApp;

use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;

interface MiniGameFactory
{
    /**
     * Create a mini-game according to the options
     *
     * @param  MiniGameId  $id
     * @param  PlayerId    $playerId
     * @param  GameOptions $options
     * @return MiniGame
     */
    public function createMiniGame(MiniGameId $id, PlayerId $playerId, GameOptions $options);
}
