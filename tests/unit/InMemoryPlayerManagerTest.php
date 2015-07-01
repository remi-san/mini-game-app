<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InMemoryPlayerManager;

class TestPlayerManager extends InMemoryPlayerManager {
    protected function getUserId($object)
    {
        $object->id;
    }

    public function create($object)
    {
        $player = \Mockery::mock('\\MiniGame\\Player');
        $player->shouldReceive('getId')->andReturn($object->id);

        return $player;
    }

    protected function supports($object)
    {
        return $object !== null;
    }
}

class InMemoryPlayerManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    /**
     * @test
     */
    public function testGetIllegalByObject()
    {
        $user = new \stdClass();
        $user->id = self::ID;

        $manager = new TestPlayerManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\UnbuildablePlayerException');

        $manager->getByObject(null);
    }

    /**
     * @test
     */
    public function testGetNonExistingByObject()
    {
        $user = new \stdClass();
        $user->id = self::ID;

        $manager = new TestPlayerManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\PlayerNotFoundException');

        $manager->getByObject($user);
    }

    /**
     * @test
     */
    public function testGetNonExistingById()
    {
        $manager = new TestPlayerManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\PlayerNotFoundException');

        $manager->get(self::ID);
    }

    /**
     * @test
     */
    public function testSave()
    {
        $manager = new TestPlayerManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $player = \Mockery::mock('\\MiniGame\\Player');
        $player->shouldReceive('getId')->andReturn(self::ID);

        $manager->save($player);

        $this->assertEquals($player, $manager->get(self::ID));
    }
}