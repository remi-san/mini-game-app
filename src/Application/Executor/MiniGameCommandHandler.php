<?php
namespace MiniGameApp\Application\Executor;

use League\Tactician\Plugins\NamedCommand\NamedCommand;
use MessageApp\Application\Response;
use MiniGame\Exceptions\GameException;
use MiniGame\Player;
use MiniGame\Result\EndGame;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\CreatePlayerCommand;
use MiniGameApp\Application\Command\GameCommand;
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
     * Executes a command and returns a response
     *
     * @param  NamedCommand $command
     * @return Response
     * @throws \Exception
     */
    public function handle(NamedCommand $command)
    {
        if (!$command instanceof GameCommand) {
            throw new \InvalidArgumentException('Command type not supported');
        }

        $player = $this->getPlayer($command);

        if ($command instanceof JoinGameCommand) {
            return $this->handleJoinGameCommand($command);
        } elseif ($command instanceof LeaveGameCommand) {
            return $this->handleLeaveGameCommand($command);
        } elseif ($command instanceof CreatePlayerCommand) {
            return $this->handleCreatePlayerCommand($command);
        } elseif ($command instanceof CreateGameCommand) {
            return $this->handleCreateGameCommand($command);
        } elseif ($command instanceof GameMoveCommand) {
            return $this->handleGameMoveCommand($command);
        }

        return $this->responseBuilder->buildResponse($player, 'Unrecognized command!');
    }

    /**
     * Retrieves the player
     *
     * @param  GameCommand $command
     * @return Player
     */
    private function getPlayer(GameCommand $command)
    {
        $player = $command->getPlayer();

        if (!$player instanceof Player) {
            throw new \InvalidArgumentException('User type not supported');
        }

        return $player;
    }

    /**
     * Handles a JoinGameCommand
     *
     * @param JoinGameCommand $command
     */
    public function handleJoinGameCommand(JoinGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented'); // TODO manage
    }

    /**
     * Handles a LeaveGameCommand
     *
     * @param LeaveGameCommand $command
     */
    public function handleLeaveGameCommand(LeaveGameCommand $command)
    {
        throw new \InvalidArgumentException('Not implemented'); // TODO manage
    }

    /**
     * Handles a CreatePlayerCommand
     *
     * @param  CreatePlayerCommand $command
     * @return string
     */
    public function handleCreatePlayerCommand(CreatePlayerCommand $command)
    {
        $player = $this->getPlayer($command);

        try {
            $this->playerManager->save($player);
            $messageText = 'Welcome!';
        } catch (PlayerException $e) {
            $messageText = 'Could not create the player!';
        }

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
        $player = $this->getPlayer($command);

        try {
            $this->gameManager->createMiniGame($command->getOptions());
            $messageText = $command->getMessage();
        } catch (\Exception $e) {
            $messageText = $e->getMessage();
        }

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
        $player = $this->getPlayer($command);

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
