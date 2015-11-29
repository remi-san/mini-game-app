<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\InDatabaseGameManager;

class TestDbGameManager extends InDatabaseGameManager
{
    public function createMiniGame(MiniGameId $id, PlayerId $playerId, GameOptions $options)
    {
        return null;
    }
}
