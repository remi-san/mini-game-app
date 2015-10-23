<?php
namespace MiniGameApp\Test;

use Broadway\EventHandling\EventBusInterface;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\TestDbGameManager;

class InDatabaseGameManagerTest extends \PHPUnit_Framework_TestCase
{
    use GameObjectMocker;

    const ID = 1;

    private $miniGame;

    private $miniGameId;

    private $player;

    private $playerId;

    public function setUp()
    {
        $this->playerId = $this->getPlayerId(1);
        $this->player = $this->getPlayer($this->playerId, 'player');

        $this->miniGameId = $this->getMiniGameId(self::ID);
        $this->miniGame = $this->getMiniGame($this->miniGameId, 'Game');
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
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andReturn($this->miniGame);

        $playerRepository = \Mockery::mock('\\MiniGameApp\\Repository\\PlayerRepository');

        $manager = new TestDbGameManager($repository, $playerRepository);

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $playerRepository = \Mockery::mock('\\MiniGameApp\\Repository\\PlayerRepository');

        $manager = new TestDbGameManager($repository, $playerRepository);

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->getMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testDeleteMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andReturn($this->miniGame);
        $repository->shouldReceive('delete')->once();

        $playerRepository = \Mockery::mock('\\MiniGameApp\\Repository\\PlayerRepository');
        $playerRepository->shouldReceive('delete')->once();

        $manager = new TestDbGameManager($repository, $playerRepository);

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));

        $manager->deleteMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testDeleteNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $playerRepository = \Mockery::mock('\\MiniGameApp\\Repository\\PlayerRepository');

        $manager = new TestDbGameManager($repository, $playerRepository);

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->deleteMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('save')->once();

        $playerRepository = \Mockery::mock('\\MiniGameApp\\Repository\\PlayerRepository');
        $playerRepository->shouldReceive('save')->once();

        $eventStream = \Mockery::mock('\\Broadway\\Domain\\DomainEventStreamInterface');

        $this->miniGame->shouldReceive('getUncommittedEvents')->andReturn($eventStream);

        $manager = new TestDbGameManager($repository, $playerRepository);

        $manager->saveMiniGame($this->miniGame);
    }
}
