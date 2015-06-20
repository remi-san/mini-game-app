<?php
namespace MiniGameApp\Application;

use Command\Response;
use MiniGame\Player;

interface MiniGameResponseBuilder {

    const HANDSHAKE = 'handshake';

    /**
     * Builds a response
     *
     * @param  Player               $player
     * @param  object|string|number $response
     * @return Response
     */
    public function buildResponse(Player $player, $response);
} 