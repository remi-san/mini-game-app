<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\InDatabasePlayerManager;

class TestDbPlayerManager extends InDatabasePlayerManager {
    protected function getUserId($object)
    {
        if (!$this->supports($object)) {
            throw new PlayerException();
        }
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

class InDatabasePlayerManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testGetIllegalByObject()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');

        $user = new \stdClass();
        $user->id = self::ID;

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\PlayerException');

        $manager->getByObject(null);
    }

    /**
     * @test
     */
    public function testGetByObject()
    {
        $player = $this->getPlayer(42, 'douglas');

        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');
        $repository->shouldReceive('find')->andReturn($player);

        $user = new \stdClass();
        $user->id = self::ID;

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $return = $manager->getByObject($user);

        $this->assertEquals($player, $return);
    }

    /**
     * @test
     */
    public function testGetNonExistingByObject()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $user = new \stdClass();
        $user->id = self::ID;

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\PlayerNotFoundException');

        $manager->getByObject($user);
    }

    /**
     * @test
     */
    public function testGetById()
    {
        $player = $this->getPlayer(42, 'douglas');

        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');
        $repository->shouldReceive('find')->andReturn($player);

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $return = $manager->get(42);

        $this->assertEquals($player, $return);
    }

    /**
     * @test
     */
    public function testGetNonExistingById()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\PlayerNotFoundException');

        $manager->get(self::ID);
    }

    /**
     * @test
     */
    public function testSave()
    {
        $player = \Mockery::mock('\\MiniGame\\Player');
        $player->shouldReceive('getId')->andReturn(self::ID);

        $repository = \Mockery::mock('\\MiniGame\\Repository\\PlayerRepository');
        $repository->shouldReceive('save')->once();
        $repository->shouldReceive('find')->andReturn($player);

        $manager = new TestDbPlayerManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->save($player);

        $this->assertEquals($player, $manager->get(self::ID));
    }
}