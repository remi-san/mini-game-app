<?php
namespace MiniGameApp\Application\Executor;

use Command\Command;
use Command\Response;
use League\Tactician\CommandBus;
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

class MiniGameCommandBus extends CommandBus implements LoggerAwareInterface
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
     * @param  Command $command
     * @return Response
     * @throws \Exception
     */
    public function execute(Command $command)
    {
        if (!$command instanceof GameCommand) {
            throw new \InvalidArgumentException('Command type not supported');
        }

        $player = $command->getPlayer();

        if (!$player instanceof Player) {
            throw new \InvalidArgumentException('User type not supported');
        }

        if ($command instanceof CreatePlayerCommand) {
            try {
                $this->playerManager->save($player);
                $messageText = 'Welcome!';
            } catch (PlayerException $e) {
                $messageText = 'Could not create the player!';
            }
        } elseif ($command instanceof CreateGameCommand) {
            try {
                $this->gameManager->createMiniGame($command->getOptions());
                $messageText = $command->getMessage();
            } catch (\Exception $e) {
                $messageText = $e->getMessage();
            }
        } else {
            if ($command instanceof JoinGameCommand) {
                throw new \InvalidArgumentException('Not implemented'); // TODO manage
            } else {
                if ($command instanceof LeaveGameCommand) {
                    throw new \InvalidArgumentException('Not implemented'); // TODO manage
                } else {
                    if ($command instanceof GameMoveCommand) {
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
                    } else {
                        $messageText = 'Unrecognized command!';
                    }
                }
            }
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
