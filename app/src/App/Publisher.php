<?php

namespace App;

use App\Transport\RabbitHandler;
use App\FileSystem\FileHandler;
use App\Helpers\IOHelper;
use App\Helpers\UrlHelper;

class Publisher
{

    private RabbitHandler $rabbitHandler;

    private int $minSleep;

    private int $maxSleep;

    public function __construct(RabbitHandler $rabbitHandler, int $minSleep, int $maxSleep)
    {
        $this->rabbitHandler = $rabbitHandler;

        $this->minSleep = $minSleep;
        $this->maxSleep = $maxSleep;
    }

    public function processFile(string $path): void
    {
        $this->rabbitHandler->registerShutdown();

        $fileHandler = new FileHandler($path);

        foreach ($fileHandler->readLine() as $line) {

            $line = trim($line);

            IOHelper::echoLine("Processing line $line");

            if (UrlHelper::validateUrl($line)) {

                $this->rabbitHandler->publish($line);
                sleep(rand($this->minSleep, $this->maxSleep));

            } else {
                IOHelper::echoLine("Line $line is not URL, skipping.");
            }

        }

        $fileHandler->close();
    }
}