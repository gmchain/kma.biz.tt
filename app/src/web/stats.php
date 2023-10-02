<?php

require_once(__DIR__ . '/../../vendor/autoload.php');

use App\Db\MariaDBHandler;
use App\Db\ClickHouseHandler;

$mariaData = (
    new MariaDBHandler(
    host: $_ENV['MARIADB_HOST'],
    database: $_ENV['MARIADB_DATABASE'],
    user: $_ENV['MARIADB_USER'],
    password: $_ENV['MARIADB_PASSWORD']
    )
)->getStats();

$clickHouseData = (
    new ClickHouseHandler(
    host: $_ENV['CLICKHOUSE_HOST'],
    database: $_ENV['CLICKHOUSE_DATABASE'],
    user: $_ENV['CLICKHOUSE_USER'],
    password: $_ENV['CLICKHOUSE_PASSWORD']
    )
)->getStats();

include "templates/page.php";