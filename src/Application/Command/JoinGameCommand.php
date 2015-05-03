<?php
namespace MiniGameApp\Application\Command;

use MessageApp\Application\Command\AbstractApplicationCommand;
use MessageApp\Application\Command\ApplicationCommand;
use MessageApp\ApplicationUser;
use MiniGame\GameOptions;

class JoinGameCommand extends AbstractApplicationCommand implements ApplicationCommand {

    /**
     * @var string
     */
    protected $gameId;

    /**
     * @var string
     */
    protected $message;

    /**
     * Construct
     *
     * @param \MessageApp\ApplicationUser $user
     * @param string          $gameId
     * @param string          $message
     */
    public function __construct(ApplicationUser $user, $gameId, $message)
    {
        $this->gameId = $gameId;
        $this->message = $message;
        parent::__construct($user);
    }

    /**
     * @return GameOptions
     */
    public function getGameId()
    {
        return $this->gameId;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }
} 