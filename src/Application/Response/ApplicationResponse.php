<?php
namespace MiniGameApp\Application\Response;

use MiniGameApp\ApplicationUser;

interface ApplicationResponse {

    /**
     * Returns the user the message must be sent to
     *
     * @return ApplicationUser
     */
    public function getUser();
}