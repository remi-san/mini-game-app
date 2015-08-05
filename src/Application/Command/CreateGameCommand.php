<?php
namespace MiniGameApp\Application\Command;

use MiniGame\Entity\MiniGameId;
use MiniGame\Entity\PlayerId;
use MiniGame\GameOptions;

class CreateGameCommand extends AbstractGameCommand
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
     * @param MiniGameId  $gameId
     * @param PlayerId    $playerId
     * @param GameOptions $options
     * @param string      $message
     */
    public function __construct(MiniGameId $gameId, PlayerId $playerId, GameOptions $options, $message)
    {
        $this->options = $options;
        $this->message = $message;
        parent::__construct($gameId, $playerId);
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
