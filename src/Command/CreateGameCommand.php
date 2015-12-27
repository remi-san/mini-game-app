<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use RemiSan\Command\Origin;

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
     * @param Origin      $origin
     */
    public function __construct(
        MiniGameId $id,
        PlayerId $playerId,
        GameOptions $options,
        $message,
        Origin $origin = null
    ) {
        parent::__construct($id, $playerId, $origin);
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
