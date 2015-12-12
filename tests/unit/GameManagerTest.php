<?php
namespace MiniGameApp\Test;

use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\TestDbGameManager;

class GameManagerTest extends \PHPUnit_Framework_TestCase
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

        $emitter = \Mockery::mock('\\League\Event\EmitterInterface');

        $manager = new TestDbGameManager($repository, $emitter);

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $emitter = \Mockery::mock('\\League\Event\EmitterInterface');

        $manager = new TestDbGameManager($repository, $emitter);

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->getMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Repository\\MiniGameRepository');
        $repository->shouldReceive('save')->once();

        $this->miniGame->shouldReceive('getUncommittedEvents')->andReturn(array());

        $emitter = \Mockery::mock('\\League\Event\EmitterInterface');

        $manager = new TestDbGameManager($repository, $emitter);

        $manager->saveMiniGame($this->miniGame);
    }
}
