<?php
namespace MiniGameApp\Application\Command;

use MessageApp\Application\Command\AbstractApplicationCommand;
use MessageApp\Application\Command\ApplicationCommand;
use MessageApp\ApplicationUser;
use MiniGame\GameOptions;

class CreateGameCommand extends AbstractApplicationCommand implements ApplicationCommand {

    /**
     * @var GameOptions
     */
    protected $options;

    /**
     * @var string
     */
    protected $message;

    /**
     * Construct
     *
     * @param \MessageApp\ApplicationUser $user
     * @param GameOptions     $options
     * @param string          $message
     */
    public function __construct(ApplicationUser $user, GameOptions $options, $message)
    {
        $this->options = $options;
        $this->message = $message;
        parent::__construct($user);
    }

    /**
     * @return GameOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
} 