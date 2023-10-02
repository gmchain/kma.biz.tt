<?php

namespace App\Db;

interface HandlerInterface
{
    public function getStats(): \Generator;
}