<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InDatabasePlayerManager;
use MiniGameApp\Test\Mock\TestDbPlayerManager;

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