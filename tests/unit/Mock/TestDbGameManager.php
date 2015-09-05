<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\InDatabaseGameManager;

class TestDbGameManager extends InDatabaseGameManager
{
    public function createMiniGame(MiniGameId $id, GameOptions $options)
    {
        return null;
    }
}
