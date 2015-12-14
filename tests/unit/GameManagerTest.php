<?php
namespace MiniGameApp\Test;

use Broadway\Domain\DateTime;
use Broadway\Domain\DomainMessage;
use Broadway\Domain\Metadata;
use League\Event\EmitterInterface;
use League\Event\EventInterface;
use MiniGame\Test\Mock\GameObjectMocker;
use MiniGameApp\Test\Mock\TestGameManager;

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
        $repository = \Mockery::mock('\\MiniGameApp\\Store\\MiniGameStore');
        $repository->shouldReceive('find')->andReturn($this->miniGame);

        $emitter = \Mockery::mock('\\League\Event\EmitterInterface');

        $manager = new TestGameManager($repository, $emitter);

        $this->assertEquals($this->miniGame, $manager->getMiniGame($this->miniGameId));
    }

    /**
     * @test
     */
    public function testGetNonExistingMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Store\\MiniGameStore');
        $repository->shouldReceive('find')->andThrow('\\Doctrine\\ORM\\ORMException');

        $emitter = \Mockery::mock('\\League\Event\EmitterInterface');

        $manager = new TestGameManager($repository, $emitter);

        $this->setExpectedException('\\MiniGameApp\\Manager\\Exceptions\\GameNotFoundException');

        $manager->getMiniGame($this->miniGameId);
    }

    /**
     * @test
     */
    public function testSaveMiniGame()
    {
        $repository = \Mockery::mock('\\MiniGameApp\\Store\\MiniGameStore');
        $repository->shouldReceive('save')->once();

        $event = \Mockery::mock(EventInterface::class);

        $this->miniGame
            ->shouldReceive('getUncommittedEvents')
            ->andReturn(
                [\Mockery::mock(new DomainMessage(null, null, new Metadata(), $event, DateTime::now()))]
            );

        $emitter = \Mockery::mock(EmitterInterface::class, function ($emitter) use ($event) {
            $emitter->shouldReceive('emit')->with($event)->once();
        });

        $manager = new TestGameManager($repository, $emitter);

        $manager->saveMiniGame($this->miniGame);
    }
}
