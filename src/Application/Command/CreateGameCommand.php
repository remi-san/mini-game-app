<?php
namespace MiniGameApp\Application\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\GameOptions;

class CreateGameCommand implements NamedCommand
{
    const NAME = 'GAME.CREATE';

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
     * @param GameOptions $options
     * @param string      $message
     */
    public function __construct(GameOptions $options, $message)
    {
        $this->options = $options;
        $this->message = $message;
    }

    /**
     * Returns the command name
     *
     * @return string
     */
    public function getCommandName()
    {
        return self::NAME;
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
