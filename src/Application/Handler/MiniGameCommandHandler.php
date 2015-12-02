<?php
namespace MiniGameApp\Application\Handler;

use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Application\Command\LeaveGameCommand;
use MiniGameApp\Application\MiniGameResponseBuilder;
use MiniGameApp\Application\Response;
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
     * @var MiniGameResponseBuilder
     */
    private $responseBuilder;

    /**
     * Constructor
     *
     * @param GameManager             $gameManager
     * @param MiniGameResponseBuilder $responseBuilder
     */
    public function __construct(
        GameManager $gameManager,
        MiniGameResponseBuilder $responseBuilder
    ) {
        $this->gameManager = $gameManager;
        $this->responseBuilder = $responseBuilder;
        $this->logger = new NullLogger();
    }

    /**
     * Handles a JoinGameCommand
     *
     * @param JoinGameCommand $command
     */
    public function handleJoinGameCommand(JoinGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented #' . $command->getGameId());
    }

    /**
     * Handles a LeaveGameCommand
     *
     * @param LeaveGameCommand $command
     */
    public function handleLeaveGameCommand(LeaveGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented #' . $command->getGameId());
    }

    /**
     * Handles a CreateGameCommand
     *
     * @param  CreateGameCommand $command
     * @return Response
     */
    public function handleCreateGameCommand(CreateGameCommand $command)
    {
        $player = reset($command->getOptions()->getPlayerOptions());
        $playerId = $player->getPlayerId();

        try {
            $miniGame = $this->gameManager->createMiniGame(
                $command->getGameId(),
                $command->getPlayerId(),
                $command->getOptions()
            );

            $messageText = $command->getMessage();

            $this->gameManager->saveMiniGame($miniGame);
        } catch (\Exception $e) {
            $messageText = $e->getMessage();
        }

        // TODO do not build response here - send event while saving
        return $this->responseBuilder->buildResponse($playerId, $messageText);
    }

    /**
     * Handles a GameMoveCommand
     *
     * @param  GameMoveCommand $command
     * @return Response
     */
    public function handleGameMoveCommand(GameMoveCommand $command)
    {
        $playerId = $command->getPlayerId();
        $messageText = null;

        try {
            $miniGame = $this->gameManager->getMiniGame($command->getGameId());
            $result = $miniGame->play($playerId, $command->getMove());
            $messageText = $result->getAsMessage();

            $this->gameManager->saveMiniGame($miniGame);
        } catch (\Exception $e) {
            $messageText = $e->getMessage();
        }

        // TODO do not build response here - send event while saving
        return $this->responseBuilder->buildResponse($playerId, $messageText); // send event ?
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
