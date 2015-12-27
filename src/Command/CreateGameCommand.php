<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;

class CreateGameCommand extends AbstractPlayerCommand
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
     * @param MiniGameId  $id
     * @param PlayerId    $playerId
     * @param GameOptions $options
     * @param string      $message
     */
    public function __construct(MiniGameId $id, PlayerId $playerId, GameOptions $options, $message)
    {
        parent::__construct($id, $playerId);
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
