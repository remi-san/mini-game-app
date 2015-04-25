<?php
namespace MiniGameApp\Application\Response\Handler;

use MiniGameApp\Application\Message;
use MiniGameApp\Application\MessageSender;
use MiniGameApp\Application\Response\ApplicationResponse;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;

class MessageResponseHandler implements ApplicationResponseHandler, LoggerAwareInterface {

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var MessageSender
     */
    protected $messageSender;

    public function __construct(MessageSender $messageSender)
    {
        $this->messageSender = $messageSender;
    }

    /**
     * @param  ApplicationResponse $response
     * @param  object $context
     * @return void
     */
    public function handle(ApplicationResponse $response = null, $context = null)
    {
        if (!$response || !($response instanceof Message)) {
            if ($this->logger) {
                $this->logger->info('Cannot handle response!');
            }
            return;
        }

        if ($this->logger) {
            $this->logger->info($response->getMessage());
        }

        $this->messageSender->send($response, $context);
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