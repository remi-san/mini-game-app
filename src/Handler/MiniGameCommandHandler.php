<?php
namespace MiniGameApp\Handler;

use MiniGameApp\Command\CreateGameCommand;
use MiniGameApp\Command\GameMoveCommand;
use MiniGameApp\Command\JoinGameCommand;
use MiniGameApp\Command\LeaveGameCommand;
use MiniGameApp\Error\ErrorEventHandler;
use MiniGameApp\Event\MiniGameAppErrorEvent;
use MiniGameApp\MiniGameFactory;
use MiniGameApp\Repository\GameRepository;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Psr\Log\NullLogger;
use RemiSan\Command\ContextContainer;

class MiniGameCommandHandler implements LoggerAwareInterface
{
    use LoggerAwareTrait;

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
        ContextContainer::setContext($command->getContext());

        try {
            $miniGame = $this->gameManager->load($command->getGameId());
            $miniGame->addPlayerToGame($command->getPlayerOptions());

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

        ContextContainer::reset();
    }

    /**
     * Handles a LeaveGameCommand
     *
     * @param  LeaveGameCommand $command
     * @return void
     */
    public function handleLeaveGameCommand(LeaveGameCommand $command)
    {
        ContextContainer::setContext($command->getContext());
        ContextContainer::reset();
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
        ContextContainer::setContext($command->getContext());

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

        ContextContainer::reset();
    }

    /**
     * Handles a GameMoveCommand
     *
     * @param  GameMoveCommand $command
     * @return void
     */
    public function handleGameMoveCommand(GameMoveCommand $command)
    {
        ContextContainer::setContext($command->getContext());

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

        ContextContainer::reset();
    }
}
