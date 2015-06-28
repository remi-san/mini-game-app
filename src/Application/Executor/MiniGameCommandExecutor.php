<?php
namespace MiniGameApp\Application\Executor;

use Command\Command;
use Command\CommandExecutor;
use Command\Response;
use MiniGame\Exceptions\GameException;
use MiniGame\GameOptions;
use MiniGame\MiniGame;
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
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class MiniGameCommandExecutor implements CommandExecutor, LoggerAwareInterface {

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
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor
     *
     * @param GameManager             $gameManager
     * @param MiniGameResponseBuilder $responseBuilder
     * @param PlayerManager           $playerManager
     */
    public function __construct(GameManager $gameManager, PlayerManager $playerManager, MiniGameResponseBuilder $responseBuilder)
    {
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
        if (! $command instanceof GameCommand) {
            throw new \InvalidArgumentException('Command type not supported');
        }

        $player = $command->getPlayer();

        if (!$player instanceof Player) {
            throw new \InvalidArgumentException('User type not supported');
        }

        if ($command instanceof CreatePlayerCommand) {
            try {
                $this->savePlayer($player);
                $messageText = 'Welcome!';
            } catch (GameException $e) {
                $messageText = 'Could not create the player!';
            }
        } elseif ($command instanceof CreateGameCommand) {
            try {
                $this->createNewMiniGame($command->getOptions());
                $messageText = $command->getMessage();
            } catch (\Exception $e) {
                $messageText = $e->getMessage();
            }
        } else if ($command instanceof JoinGameCommand) {
            throw new \InvalidArgumentException('Not implemented'); // TODO manage
        } else if ($command instanceof LeaveGameCommand) {
            throw new \InvalidArgumentException('Not implemented'); // TODO manage
        } else if ($command instanceof GameMoveCommand) {
            try {
                $miniGame = $this->getPlayerMiniGame($player);
                $result = $miniGame->play($player, $command->getMove());
                $messageText = $result->getAsMessage();

                if ($result instanceof EndGame) {
                    $this->deletePlayerMiniGame($player);
                }
            } catch (GameException $ge) {
                $messageText = $ge->getMessage() . ' ' . $ge->getResult()->getAsMessage();
            } catch (GameNotFoundException $gnfe) {
                $messageText = 'You have to start/join a game first!';
            }
        } else {
            $messageText = 'Unrecognized command!';
        }

        return $this->responseBuilder->buildResponse($player, $messageText);
    }

    /**
     * Saves a player
     *
     * @param  Player $player
     * @return void
     */
    protected function savePlayer(Player $player)
    {
        $this->playerManager->save($player);
    }

    /**
     * Creates a new game for the player
     *
     * @param  GameOptions $options
     * @return void
     */
    protected function createNewMiniGame(GameOptions $options) {
        $this->gameManager->createMiniGame($options);
    }

    /**
     * Deletes ended game for the player
     *
     * @param Player $player
     */
    protected function deletePlayerMiniGame(Player $player) {
        $miniGame = $this->getPlayerMiniGame($player);
        $this->gameManager->deleteMiniGame($miniGame->getId());
    }

    /**
     * Returns the current game for the player
     *
     * @param  Player $player
     * @return MiniGame
     */
    protected function getPlayerMiniGame(Player $player) {
        return $this->gameManager->getActiveMiniGameForPlayer($player);
    }

    /**
     * Sets a logger instance on the object
     *
     * @param LoggerInterface $logger
     * @return null
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
} 