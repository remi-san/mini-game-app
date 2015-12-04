<?php
namespace MiniGameApp\Application\Handler;

use League\Event\EmitterInterface;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Application\Command\LeaveGameCommand;
use MiniGameApp\Application\Event\MiniGameAppErrorEvent;
use MiniGameApp\Manager\GameManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MiniGameCommandHandler implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /**
     * Constructor
     *
     * @param GameManager      $gameManager
     * @param EmitterInterface $eventEmitter
     */
    public function __construct(
        GameManager $gameManager,
        EmitterInterface $eventEmitter
    ) {
        $this->gameManager = $gameManager;
        $this->eventEmitter = $eventEmitter;
        $this->logger = new NullLogger();
    }

    /**
     * Handles a JoinGameCommand
     *
     * @param JoinGameCommand $command
     * @return void
     */
    public function handleJoinGameCommand(JoinGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented #' . $command->getGameId());
    }

    /**
     * Handles a LeaveGameCommand
     *
     * @param LeaveGameCommand $command
     * @return void
     */
    public function handleLeaveGameCommand(LeaveGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented #' . $command->getGameId());
    }

    /**
     * Handles a CreateGameCommand
     *
     * @param  CreateGameCommand $command
     * @return void
     */
    public function handleCreateGameCommand(CreateGameCommand $command)
    {
        try {
            $miniGame = $this->gameManager->createMiniGame(
                $command->getGameId(),
                $command->getPlayerId(),
                $command->getOptions()
            );

            $this->gameManager->saveMiniGame($miniGame);
        } catch (\Exception $e) {
            $this->eventEmitter->emit(
                new MiniGameAppErrorEvent(
                    $command->getGameId(),
                    $command->getPlayerId(),
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * Handles a GameMoveCommand
     *
     * @param  GameMoveCommand $command
     * @return void
     */
    public function handleGameMoveCommand(GameMoveCommand $command)
    {
        try {
            $miniGame = $this->gameManager->getMiniGame($command->getGameId());
            $miniGame->play($command->getPlayerId(), $command->getMove());

            $this->gameManager->saveMiniGame($miniGame);
        } catch (\Exception $e) {
            $this->eventEmitter->emit(
                new MiniGameAppErrorEvent(
                    $command->getGameId(),
                    $command->getPlayerId(),
                    $e->getMessage()
                )
            );
        }
    }

    /**
     * Sets a logger instance on the object
     *
     * @param  LoggerInterface $logger
     * @return void
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
