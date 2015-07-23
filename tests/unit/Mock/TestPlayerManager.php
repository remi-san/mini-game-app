<?php
namespace MiniGameApp\Test\Mock;

use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\InMemoryPlayerManager;

class TestPlayerManager extends InMemoryPlayerManager
{
    public function create($object)
    {
        $player = \Mockery::mock('\\MiniGame\\Player');
        $player->shouldReceive('getId')->andReturn($object->id);

        return $player;
    }

    protected function getUserId($object)
    {
        if (!$this->supports($object)) {
            throw new PlayerException();
        }
        $object->id;
    }

    protected function supports($object)
    {
        return $object !== null;
    }
}
