<?php
namespace MiniGameApp\Application;

use MessageApp\Application\Response\ApplicationResponse;
use MiniGame\Entity\PlayerId;

interface MiniGameResponseBuilder
{
    /**
     * Builds a response
     *
     * @param  PlayerId             $player
     * @param  object|string|number $response
     * @return ApplicationResponse
     */
    public function buildResponse(PlayerId $player, $response);
}
