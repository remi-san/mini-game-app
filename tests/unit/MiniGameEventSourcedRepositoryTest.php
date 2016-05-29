<?php

namespace MiniGameApp\Test;

use Broadway\Domain\AggregateRoot;
use Broadway\EventSourcing\EventSourcingRepository;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Repository\EventSourced\MiniGameEventSourcedRepository;
use MiniGameApp\Test\Mock\AggregateRootMiniGame;

class MiniGameEventSourcedRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var EventSourcingRepository
     */
    private $repository;

    public function setUp()
    {
        $this->repository = \Mockery::mock(EventSourcingRepository::class);
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
        $game = \Mockery::mock(MiniGame::class);

        $repository = new MiniGameEventSourcedRepository($this->repository);

        $this->setExpectedException(\InvalidArgumentException::class);

        $repository->save($game);
    }

    /**
     * @test
     */
    public function itShouldDeferSaveToInnerRepository()
    {
        $game = new AggregateRootMiniGame();

        $repository = new MiniGameEventSourcedRepository($this->repository);

        $this->repository->shouldReceive('save')->with($game);

        $repository->save($game);
    }

    /**
     * @test
     */
    public function itShouldUseTheInnerRepositoryToLoadTheMiniGame()
    {
        $gameId = \Mockery::mock(MiniGameId::class);
        $game = new AggregateRootMiniGame();

        $repository = new MiniGameEventSourcedRepository($this->repository);

        $this->repository->shouldReceive('load')->with($gameId)->andReturn($game);

        $returnUser = $repository->load($gameId);

        $this->assertEquals($returnUser, $game);
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfTheInnerRepositoryReturnsNull()
    {
        $gameId = \Mockery::mock(MiniGameId::class);

        $repository = new MiniGameEventSourcedRepository($this->repository);

        $this->repository->shouldReceive('load')->with($gameId)->andReturn(null);

        $this->assertNull($repository->load($gameId));
    }

    /**
     * @test
     */
    public function itShouldThrowAnExceptionIfTheLoadedEntityIsNotAMiniGame()
    {
        $gameId = \Mockery::mock(MiniGameId::class);
        $game = \Mockery::mock(AggregateRoot::class);

        $repository = new MiniGameEventSourcedRepository($this->repository);

        $this->repository->shouldReceive('load')->with($gameId)->andReturn($game);
        $this->setExpectedException(\InvalidArgumentException::class);

        $repository->load($gameId);
    }
}
