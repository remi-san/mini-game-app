<?php
namespace MiniGameApp\Application\Response;

use MiniGameApp\ApplicationUser;

class SendMessageResponse implements ApplicationResponse {

    /**
     * @var string
     */
    protected $message;

    /**
     * @var ApplicationUser
     */
    protected $user;

    /**
     * Construct
     *
     * @param ApplicationUser $user
     * @param $message
     */
    public function __construct(ApplicationUser $user, $message) {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Returns the message
     *
     * @return string
     */
    public function getMessage() {
        return $this->message;
    }

    /**
     * Returns the user the message must be sent to
     *
     * @return ApplicationUser
     */
    public function getUser() {
        return $this->user;
    }
} 