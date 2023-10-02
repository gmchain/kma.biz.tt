<?php

namespace App\Transport;

use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AbstractConnection;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exchange\AMQPExchangeType;
use PhpAmqpLib\Message\AMQPMessage;

class RabbitHandler
{

    const EXCHANGE = 'test';

    const QUEUE = 'urls';

    const CONSUMER_TAG = 'consumer';

    private AbstractConnection $connection;

    private AMQPChannel $channel;

    private $callback;

    public function __construct(
        string $host,
        string $user,
        string $password
    ) {
        $this->connection = new AMQPStreamConnection(host: $host, port: 5672, user: $user, password: $password);
        $this->channel = $this->connection->channel();

        $this->channel->queue_declare(self::QUEUE, false, true, false, false);
        $this->channel->exchange_declare(self::EXCHANGE, AMQPExchangeType::DIRECT, false, true, false);
        $this->channel->queue_bind(self::QUEUE, self::EXCHANGE);
    }

    public function consume(callable $callback): void
    {
        $this->channel->basic_consume(self::QUEUE, self::CONSUMER_TAG, false, false, false, false, $callback);

        $this->registerShutdown();

        $this->channel->consume();
    }

    public function publish(string $line): void
    {
        $message = new AMQPMessage($line, [
            'content_type' => 'text/plain',
            'delivery_mode' => AMQPMessage::DELIVERY_MODE_PERSISTENT
        ]);

        $this->channel->basic_publish($message, self::EXCHANGE);
    }

    public function registerShutdown(): void
    {
        register_shutdown_function([$this, 'shutdown']);
    }

    public function shutdown(): void
    {
        $this->channel->close();
        $this->connection->close();
    }
}