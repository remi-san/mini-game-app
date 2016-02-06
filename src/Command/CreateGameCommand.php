<?php
namespace MiniGameApp\Command;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;
use RemiSan\Command\Context;

class CreateGameCommand extends AbstractPlayerCommand
{
    const NAME = 'GAME.CREATE';

    /**
     * @var GameOptions
     */
    private $options;

    /**
     * @var string
     */
    private $message;

    /**
     * Construct.
     */
    public function __construct()
    {
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

    /**
     * Construct
     *
     * @param MiniGameId  $id
     * @param PlayerId    $playerId
     * @param GameOptions $options
     * @param string      $message
     * @param Context      $origin
     *
     * @return CreateGameCommand
     */
    public static function create(
        MiniGameId $id = null,
        PlayerId $playerId = null,
        GameOptions $options = null,
        $message = null,
        Context $origin = null
    ) {
        $obj = new self();

        $obj->init($id, $playerId, $origin);
        $obj->options = $options;
        $obj->message = $message;

        return $obj;
    }
}
