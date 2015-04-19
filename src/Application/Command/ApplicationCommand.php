<?php
namespace MiniGameApp\Application\Command;

use MiniGameApp\ApplicationUser;

interface ApplicationCommand {

    /**
     * @return \MiniGameApp\ApplicationUser
     */
    public function getUser();
} 