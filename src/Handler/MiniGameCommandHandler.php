<?php
namespace MiniGameApp\Handler;

use League\Event\EmitterInterface;
use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\JoinGameCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\MiniGameBuilder;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MiniGameCommandHandler implements LoggerAwareInterface
{
    /**
     * @var MiniGameBuilder
     */
    protected $builder;

    /**
     * @var GameManager
     */
    private $gameManager;

    /**
     * @var EmitterInterface
     */
    private $eventEmitter;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param MiniGameBuilder  $builder
     * @param GameManager      $gameManager
     * @param EmitterInterface $eventEmitter
     */
    public function __construct(
        MiniGameBuilder $builder,
        GameManager $gameManager,
        EmitterInterface $eventEmitter
    ) {
        $this->builder = $builder;
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
     * @param  LeaveGameCommand $command
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
            $miniGame = $this->builder->createMiniGame(
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
     * @codeCoverageIgnore
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
