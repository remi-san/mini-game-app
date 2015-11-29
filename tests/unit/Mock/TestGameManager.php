<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\InMemoryGameManager;

class TestGameManager extends InMemoryGameManager
{
    public function createMiniGame(MiniGameId $id, PlayerId $playerId, GameOptions $options)
    {
        return null;
    }
}
