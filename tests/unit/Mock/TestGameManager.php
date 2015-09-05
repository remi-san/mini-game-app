<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\InMemoryGameManager;

class TestGameManager extends InMemoryGameManager
{
    public function createMiniGame(MiniGameId $id, GameOptions $options)
    {
        return null;
    }
}
