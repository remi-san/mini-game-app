<?php

namespace MiniGameApp\Test\Repository\EventSourced;

use Broadway\Domain\AggregateRoot;
use Broadway\EventSourcing\EventSourcingRepository;
use Faker\Factory;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Repository\EventSourced\MiniGameEventSourcedRepository;
use MiniGameApp\Test\Mock\AggregateRootMiniGame;
use Mockery\Mock;

class MiniGameEventSourcedRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @var MiniGameId */
    private $gameId;

    /** @var MiniGame */
    private $game;

    /** @var EventSourcingRepository | Mock */
    private $eventSourcingRepository;

    /** @var MiniGameEventSourcedRepository */
    private $miniGameRepository;

    public function setUp()
    {
        $faker = Factory::create();

        $this->gameId = MiniGameId::create($faker->uuid);

        $this->eventSourcingRepository = \Mockery::mock(EventSourcingRepository::class);

        $this->miniGameRepository = new MiniGameEventSourcedRepository($this->eventSourcingRepository);
    }

    public function tearDown()
    {
        \Mockery::close();
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfMiniGameIsNotAnAggregateRoot()
    {
        $this->givenGameIsNotAnAggregateRoot();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->miniGameRepository->save($this->game);
    }

    /**
     * @test
     */
    public function itShouldDeferSaveToInnerRepository()
    {
        $this->givenGameIsValid();
        $this->assertGameWillBeSaved();

        $this->miniGameRepository->save($this->game);
    }

    /**
     * @test
     */
    public function itShouldUseTheInnerRepositoryToLoadTheMiniGame()
    {
        $this->givenGameIsValid();
        $this->givenGameExists();

        $returnUser = $this->miniGameRepository->load($this->gameId);

        $this->assertEquals($returnUser, $this->game);
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfTheInnerRepositoryReturnsNull()
    {
        $this->givenGameDoesNotExist();

        $this->assertNull($this->miniGameRepository->load($this->gameId));
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfTheLoadedEntityIsNotAMiniGame()
    {
        $this->givenAggregateRootIsNotAGame();
        $this->givenGameExists();

        $this->setExpectedException(\InvalidArgumentException::class);

        $this->miniGameRepository->load($this->gameId);
    }

    private function assertGameWillBeSaved()
    {
        $this->eventSourcingRepository->shouldReceive('save')->with($this->game)->once();
    }

    private function givenGameExists()
    {
        $this->eventSourcingRepository->shouldReceive('load')->with($this->gameId)->andReturn($this->game);
    }

    private function givenGameIsValid()
    {
        $this->game = new AggregateRootMiniGame();
    }

    private function givenGameIsNotAnAggregateRoot()
    {
        $this->game = \Mockery::mock(MiniGame::class);
    }

    private function givenAggregateRootIsNotAGame()
    {
        $this->game = \Mockery::mock(AggregateRoot::class);
    }

    private function givenGameDoesNotExist()
    {
        $this->eventSourcingRepository->shouldReceive('load')->with($this->gameId)->andReturn(null);
    }
}
