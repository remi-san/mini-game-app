<?php

namespace MiniGameApp\Finder;

use MiniGameApp\ReadModel\MiniGame;

interface GameFinder
{
    /**
     * @param  mixed $id
     *
     * @return MiniGame
     */
    public function find($id);

    /**
     * @param  MiniGame $game
     *
     * @return void
     */
    public function save(MiniGame $game);
}
