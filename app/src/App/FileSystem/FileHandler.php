<?php

namespace App\FileSystem;

class FileHandler
{
    private mixed $handle;

    public function __construct(string $fileName)
    {
        $this->handle = fopen($fileName, 'r');

        if (!$this->handle) {
            throw new \Exception("Cannot read file $fileName");
        }
    }

    public function readLine(): \Generator
    {
        while (($line = fgets($this->handle)) !== false) {
            yield $line;
        }
    }

    public function close()
    {
        fclose($this->handle);
    }
}