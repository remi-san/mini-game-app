<?php
namespace MiniGameApp\Application\Executor;

use MiniGame\Exceptions\GameException;
use MiniGame\GameOptions;
use MiniGame\MiniGame;
use MiniGame\Player;
use MiniGame\Result\EndGame;
use MiniGameApp\Application\Command\ApplicationCommand;
use MiniGameApp\Application\Command\CreateGameCommand;
use MiniGameApp\Application\Command\GameMoveCommand;
use MiniGameApp\Application\Command\JoinGameCommand;
use MiniGameApp\Application\CommandExecutor;
use MiniGameApp\Application\Response\ApplicationResponse;
use MiniGameApp\Manager\Exceptions\GameNotFoundException;
use MiniGameApp\Manager\GameManager;
use MiniGameApp\Manager\PlayerManager;

class MiniGameCommandExecutor implements CommandExecutor {

    /**
     * @var \MiniGameApp\Manager\PlayerManager
     */
    private $playerManager;

    /**
     * @var \MiniGameApp\Manager\GameManager
     */
    private $gameManager;

    /**
     * Constructor
     *
     * @param GameManager $gameManager
     * @param PlayerManager $playerManager
     */
    public function __construct(GameManager $gameManager, PlayerManager $playerManager)
    {
        $this->gameManager = $gameManager;
        $this->playerManager = $playerManager;
    }

    /**
     * Executes a command and returns a response
     *
     * @param ApplicationCommand $command
     * @return \MiniGameApp\Application\Response\ApplicationResponse
     */
    public function execute(ApplicationCommand $command)
    {
        $player = $this->playerManager->getPlayer($command->getUser());

        if ($command instanceof CreateGameCommand) {
            try {
                $this->createNewMiniGame($command->getOptions());
                $messageText = $command->getMessage();
            } catch (\Exception $e) {
                $messageText = $e->getMessage();
            }
        } else if ($command instanceof JoinGameCommand) {
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
        return new ApplicationResponse($command->getUser(), $messageText);
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
} 