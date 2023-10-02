<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use App\Consumer;
use App\Db\MariaDBHandler;
use App\Transport\RabbitHandler;
use App\Helpers\IOHelper;

$errorCode = 0;

try {

    (
        new Consumer(
            new MariaDBHandler(
            host: $_ENV['MARIADB_HOST'],
            user: $_ENV['MARIADB_USER'],
            password: $_ENV['MARIADB_PASSWORD'],
            database: $_ENV['MARIADB_DATABASE']
            ),
            new RabbitHandler(
            host: $_ENV['RABBITMQ_HOST'],
            user: $_ENV['RABBITMQ_USER'],
            password: $_ENV['RABBITMQ_PASSWORD']
            )
        )
    )->consume();
} catch (\Exception $exception) {

    IOHelper::echoLine($exception->getMessage());
    $errorCode = 1;

}

exit($errorCode);