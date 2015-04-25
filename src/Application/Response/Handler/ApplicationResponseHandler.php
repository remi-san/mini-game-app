<?php
namespace MiniGameApp\Application\Response\Handler;

use MiniGameApp\Application\Response\ApplicationResponse;

interface ApplicationResponseHandler {

    /**
     * @param  ApplicationResponse $response
     * @param  object              $context
     * @return void
     */
    public function handle(ApplicationResponse $response = null, $context = null);
} 