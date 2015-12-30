<?php
namespace MiniGameApp\Handler;

use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\JoinGameCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\MiniGameFactory;
use MiniGameApp\Repository\GameRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use RemiSan\Command\ErrorEventHandler;

class MiniGameCommandHandler implements LoggerAwareInterface
{
    /**
     * @var MiniGameFactory
     */
    protected $builder;

    /**
     * @var GameRepository
     */
    private $gameManager;

    /**
     * @var ErrorEventHandler
     */
    private $errorHandler;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param MiniGameFactory   $builder
     * @param GameRepository    $gameManager
     * @param ErrorEventHandler $errorHandler
     */
    public function __construct(
        MiniGameFactory $builder,
        GameRepository $gameManager,
        ErrorEventHandler $errorHandler
    ) {
        $this->builder = $builder;
        $this->gameManager = $gameManager;
        $this->errorHandler = $errorHandler;
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

            $this->gameManager->save($miniGame);
        } catch (\Exception $e) {
            $this->errorHandler->handle(
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
            $miniGame = $this->gameManager->load($command->getGameId());
            $miniGame->play($command->getPlayerId(), $command->getMove());

            $this->gameManager->save($miniGame);
        } catch (\Exception $e) {
            $this->errorHandler->handle(
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
