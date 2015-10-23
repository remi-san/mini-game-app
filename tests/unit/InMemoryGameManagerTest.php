<?php
namespace MiniGameApp\Test;

use Broadway\EventHandling\EventBusInterface;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\TestGameManager;

class InMemoryGameManagerTest extends \PHPUnit_Framework_TestCase
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
        $manager = new TestGameManager(array($this->miniGameId, $this->miniGame), array());
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {
        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->getMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testDeleteMiniGame()
    {
        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager(array(self::ID, $this->miniGame), array());
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));

        $manager->deleteMiniGame($this->miniGameId);
        $manager->getMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testDeleteNonExistingMiniGame()
    {
        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager = new TestGameManager();
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->deleteMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {
        $eventStream = \Mockery::mock('\\Broadway\\Domain\\DomainEventStreamInterface');

        $this->miniGame->shouldReceive('getUncommittedEvents')->andReturn($eventStream);

        $manager = new TestGameManager(array(), array());
        $manager->setLogger(\Mockery::mock('\\Psr\\Log\\LoggerInterface'));

        $manager->saveMiniGame($this->miniGame);

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));
    }
}
