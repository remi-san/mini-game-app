<?php
namespace MiniGameApp\Application\Command;

use MiniGameApp\ApplicationUser;

interface ApplicationCommand {

    /**
     * @return ApplicationUser
     */
    public function getUser();
} 