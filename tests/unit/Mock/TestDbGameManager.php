<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\GameOptions;
use MiniGameApp\Manager\InDatabaseGameManager;

class TestDbGameManager extends InDatabaseGameManager
{
    public function createMiniGame(GameOptions $options)
    {
        return null;
    }
}
