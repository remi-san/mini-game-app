<?php
namespace MiniGameApp\Test\Mock;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use MiniGameApp\Manager\AbstractGameManager;

class TestDbGameManager extends AbstractGameManager
{
    public function createMiniGame(MiniGameId $id, PlayerId $playerId, GameOptions $options)
    {
        return null;
    }
}
