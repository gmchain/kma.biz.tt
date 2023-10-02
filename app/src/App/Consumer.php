<?php

namespace App;

use PhpAmqpLib\Message\AMQPMessage;
use App\Transport\RabbitHandler;
use App\Db\MariaDBHandler;
use App\Helpers\IOHelper;
use App\Helpers\UrlHelper;

class Consumer
{
    private MariaDBHandler $mariaDbHandler;

    private RabbitHandler $rabbitHandler;

    public function __construct(MariaDBHandler $mariaDbHandler, RabbitHandler $rabbitHandler)
    {
        $this->rabbitHandler = $rabbitHandler;
        $this->mariaDbHandler = $mariaDbHandler;
    }

    public function consume(): void
    {
        $this->rabbitHandler->consume([$this, 'processMessage']);
    }

    public function processMessage(AMQPMessage $message): void
    {
        IOHelper::echoLine('Processing message: ' . $message->body);

        $url = $message->body;
        $length = UrlHelper::getUrlLength($url);
        $timestamp = $message->get('timestamp');

        $this->mariaDbHandler->insert($url, $length, $timestamp);

        $message->ack();
    }
}