<?php
namespace MiniGameApp\Response;

use MiniGame\Entity\PlayerId;
use MiniGameApp\Response;

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
