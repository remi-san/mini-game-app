<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\GameOptions;
use MiniGameApp\Manager\InMemoryGameManager;

class TestGameManager extends InMemoryGameManager
{
    public function createMiniGame(GameOptions $options)
    {
        return null;
    }
}
