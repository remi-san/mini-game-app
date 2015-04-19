<?php
namespace MiniGameApp\Application\Command;

use MiniGameApp\ApplicationUser;

class AbstractApplicationCommand implements ApplicationCommand {

    /**
     * @var ApplicationUser
     */
    protected $user;

    /**
     * @param \MiniGameApp\ApplicationUser $user
     */
    public function __construct(ApplicationUser $user)
    {
        $this->user = $user;
    }

    /**
     * @return \MiniGameApp\ApplicationUser
     */
    public function getUser()
    {
        return $this->user;
    }
} 