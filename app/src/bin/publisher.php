<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use App\Publisher;
use App\Helpers\IOHelper;
use App\Transport\RabbitHandler;

$errorCode = 0;

if (count($argv) == 0) {

    IOHelper::echoLine("No file specified. Exiting");
    $errorCode = 1;
} else {

    try {
        (
            new Publisher(
            rabbitHandler: new RabbitHandler(
                host: $_ENV['RABBITMQ_HOST'],
                user: $_ENV['RABBITMQ_USER'],
                password: $_ENV['RABBITMQ_PASSWORD']
                ),
            minSleep: (int) ($_ENV['MIN_SLEEP'] ?: 10),
            maxSleep: (int) ($_ENV['MAX_SLEEP'] ?: 100)
            )
        )->processFile($argv[1]);

    } catch (\Exception $exception) {

        IOHelper::echoLine($exception->getMessage());
        $errorCode = 1;

    }

}


exit($errorCode);