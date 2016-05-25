<?php

namespace MiniGameApp\Repository\EventSourced;

use Broadway\EventSourcing\EventSourcingRepository;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGameApp\Exception\GameNotFoundException;
use MiniGameApp\Repository\GameRepository;

class MiniGameEventSourcedRepository implements GameRepository
{
    /**
     * @var EventSourcingRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * @param EventSourcingRepository $repository
     */
    public function __construct(EventSourcingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Saves a mini-game
     *
     * @param  MiniGame $game
     * @return MiniGame
     */
    public function save(MiniGame $game)
    {
        $this->repository->save($game);
    }

    /**
     * Get the mini-game corresponding to the id
     *
     * @param  MiniGameId $id
     * @throws GameNotFoundException
     * @return MiniGame
     */
    public function load(MiniGameId $id)
    {
        return $this->repository->load($id);
    }
}
