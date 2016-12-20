<?php

namespace MiniGameApp\Test\Mock;

use Broadway\Domain\AggregateRoot;
use Broadway\Domain\DomainEventStream;
use MiniGame\Entity\MiniGame;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\Player;
use MiniGame\Entity\PlayerId;
use MiniGame\GameResult;
use MiniGame\Move;
use MiniGame\PlayerOptions;

class AggregateRootMiniGame implements MiniGame, AggregateRoot
{
    /**
     * @return DomainEventStream
     */
    public function getUncommittedEvents()
    {
        return [];
    }

    /**
     * @return string
     */
    public function getAggregateRootId()
    {
        return 0;
    }

    /**
     * Returns the name of the mini-game
     * @return string
     */
    public static function getName()
    {
        return 'name';
    }

    /**
     * Returns the id of the game (unique string)
     *
     * @return MiniGameId
     */
    public function getId()
    {
        return MiniGameId::create();
    }

    /**
     * Starts the game
     *
     * @param  PlayerId $playerId
     *
     * @return GameResult
     */
    public function startGame(PlayerId $playerId)
    {
        return null;
    }

    /**
     * Adds a player to the game
     *
     * @param  PlayerOptions $playerOptions
     * @return GameResult
     */
    public function addPlayerToGame(PlayerOptions $playerOptions)
    {
        return null;
    }

    /**
     * A player leaves the game
     *
     * @param PlayerId $playerId
     * @return GameResult
     */
    public function leaveGame(PlayerId $playerId)
    {
        return null;
    }

    /**
     * Allows the player to play the game
     *
     * @param  PlayerId $player
     * @param  Move $move
     * @return GameResult
     */
    public function play(PlayerId $player, Move $move)
    {
        return null;
    }

    /**
     * Is it the player's turn?
     *
     * @param  PlayerId $player
     * @return bool
     */
    public function canPlayerPlay(PlayerId $player)
    {
        return false;
    }

    /**
     * Get the players
     *
     * @return Player[]
     */
    public function getPlayers()
    {
        return [];
    }
} 