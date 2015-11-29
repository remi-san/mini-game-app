<?php
namespace MiniGameApp\Listener;

use League\Event\AbstractListener;
use League\Event\EventInterface;
use MiniGame\GameResult;
use MiniGameApp\Application\MiniGameResponseBuilder;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class GameResultListener extends AbstractListener implements LoggerAwareInterface
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MiniGameResponseBuilder
     */
    private $responseBuilder;

    /**
     * Constructor
     *
     * @param MiniGameResponseBuilder $responseBuilder
     */
    public function __construct(
        MiniGameResponseBuilder $responseBuilder
    ) {
        $this->responseBuilder = $responseBuilder;
        $this->logger = new NullLogger();
    }

    /**
     * Handle an event.
     *
     * @param EventInterface $event
     *
     * @return void
     */
    public function handle(EventInterface $event)
    {
        if (! $event instanceof GameResult) {
            return;
        }

        $this->responseBuilder->buildResponse($event->getPlayerId(), $event->getAsMessage());
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
