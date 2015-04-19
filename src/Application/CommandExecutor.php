<?php
namespace MiniGameApp\Application;

use MiniGameApp\Application\Command\ApplicationCommand;
use MiniGameApp\Application\Response\ApplicationResponse;

interface CommandExecutor {

    /**
     * Executes a command and returns a response
     *
     * @param  \MiniGameApp\Application\Command\ApplicationCommand $command
     * @return \MiniGameApp\Application\Response\ApplicationResponse
     */
    public function execute(ApplicationCommand $command);
}