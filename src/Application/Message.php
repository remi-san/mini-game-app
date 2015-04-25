<?php
namespace MiniGameApp\Application;

use MiniGameApp\ApplicationUser;

interface Message {

    /**
     * Returns the message
     *
     * @return string
     */
    public function getMessage();

    /**
     * Returns the user the message must be sent to
     *
     * @return ApplicationUser
     */
    public function getUser();
} 