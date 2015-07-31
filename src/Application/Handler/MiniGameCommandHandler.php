<?php
namespace MiniGameApp\Application\Handler;

use MessageApp\Application\Response;
use MiniGame\Exceptions\GameException;
use MiniGame\Result\EndGame;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\CreatePlayerCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Application\Command\LeaveGameCommand;
use MiniGameApp\Application\MiniGameResponseBuilder;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Manager\Exceptions\PlayerException;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
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
     * @var PlayerManager
     */
    private $playerManager;
    /**
     * @var MiniGameResponseBuilder
     */
    private $responseBuilder;

    /**
     * Constructor
     *
     * @param GameManager             $gameManager
     * @param MiniGameResponseBuilder $responseBuilder
     * @param PlayerManager           $playerManager
     */
    public function __construct(
        GameManager $gameManager,
        PlayerManager $playerManager,
        MiniGameResponseBuilder $responseBuilder
    ) {
        $this->gameManager = $gameManager;
        $this->playerManager = $playerManager;
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
        throw new \InvalidArgumentException('Not implemented');
    }

    /**
     * Handles a LeaveGameCommand
     *
     * @param LeaveGameCommand $command
     */
    public function handleLeaveGameCommand(LeaveGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented');
    }

    /**
     * Handles a CreatePlayerCommand
     *
     * @param  CreatePlayerCommand $command
     * @return string
     */
    public function handleCreatePlayerCommand(CreatePlayerCommand $command)
    {
        $player = $command->getPlayer();

        try {
            $this->playerManager->save($player);
            $messageText = 'Welcome!';
        } catch (PlayerException $e) {
            $messageText = 'Could not create the player!';
        }

        // TODO do not build response here - send event while saving
        return $this->responseBuilder->buildResponse($player, $messageText);
    }

    /**
     * Handles a CreateGameCommand
     *
     * @param  CreateGameCommand $command
     * @return string
     */
    public function handleCreateGameCommand(CreateGameCommand $command)
    {
        $player = $command->getPlayer();

        try {
            $this->gameManager->createMiniGame($command->getOptions());
            $messageText = $command->getMessage();
        } catch (\Exception $e) {
            $messageText = $e->getMessage();
        }

        // TODO do not build response here - send event while saving
        return $this->responseBuilder->buildResponse($player, $messageText);
    }

    /**
     * Handles a GameMoveCommand
     *
     * @param  GameMoveCommand $command
     * @return string
     */
    public function handleGameMoveCommand(GameMoveCommand $command)
    {
        $player = $command->getPlayer();

        try {
            $miniGame = $this->gameManager->getActiveMiniGameForPlayer($player);
            $result = $miniGame->play($player, $command->getMove());
            $messageText = $result->getAsMessage();

            if ($result instanceof EndGame) {
                $this->gameManager->deleteMiniGame($miniGame->getId());
            } else {
                $this->gameManager->saveMiniGame($miniGame);
            }
        } catch (GameException $ge) {
            $messageText = $ge->getMessage() . ' ' . $ge->getResult()->getAsMessage();
        } catch (GameNotFoundException $gnfe) {
            $messageText = 'You have to start/join a game first!';
        }

        // TODO do not build response here - send event while saving
        return $this->responseBuilder->buildResponse($player, $messageText);
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
