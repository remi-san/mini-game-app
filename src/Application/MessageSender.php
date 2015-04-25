<?php
namespace MiniGameApp\Application;

interface MessageSender {

    /**
     * @param  Message $message
     * @param  object  $context
     * @return void
     */
    public function send(Message $message, $context);
} 