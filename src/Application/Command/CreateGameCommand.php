<?php
namespace MiniGameApp\Application\Command;

use MiniGame\GameOptions;
use MiniGame\Player;

class CreateGameCommand extends AbstractGameCommand
{

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
     * @param Player      $player
     * @param GameOptions $options
     * @param string      $message
     */
    public function __construct(Player $player, GameOptions $options, $message)
    {
        $this->options = $options;
        $this->message = $message;
        parent::__construct($player);
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
