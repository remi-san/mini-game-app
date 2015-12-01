<?php
namespace MiniGameApp\Application;

use MiniGame\Entity\PlayerId;

interface MiniGameResponseBuilder
{
    /**
     * Builds a response
     *
     * @param  PlayerId             $player
     * @param  object|string|number $response
     * @return Response
     */
    public function buildResponse(PlayerId $player, $response);
}
