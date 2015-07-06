<?php
namespace MiniGameApp\Test;

use MiniGame\GameOptions;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Manager\InDatabaseGameManager;

class TestDbGameManager extends InDatabaseGameManager {
    public function createMiniGame(GameOptions $options) {
        return null;
    }
}

class InDatabaseGameManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    private $miniGame;

    private $player;

    public function setUp()
    {
        $this->player = $this->getPlayer(1, 'player');

        $this->miniGame = $this->getMiniGame(self::ID, 'Game');
        $this->miniGame->shouldReceive('getPlayers')->andReturn(array($this->player));
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function testGetMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andReturn($this->miniGame);

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame(self::ID));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->getMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testGetActiveMiniGameForPlayer()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('findPlayerMinigame')->andReturn($this->miniGame);

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getActiveMiniGameForPlayer($this->player));
    }

    /**
     * @test
     */
    public function testGetNonExistingActiveMiniGameForPlayer()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('findPlayerMinigame')->andThrow('\\Doctrine\\ORM\\ORMException');

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->getActiveMiniGameForPlayer($this->player);
    }

    /**
     * @test
     */
    public function testDeleteMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andReturn($this->miniGame);
        $repository->shouldReceive('delete')->once();

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame(self::ID));

        $manager->deleteMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testDeleteNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->deleteMiniGame(self::ID);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGame\\Repository\\MiniGameRepository');
        $repository->shouldReceive('save')->once();

        $manager = new TestDbGameManager($repository);
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->saveMiniGame($this->miniGame);
    }
}