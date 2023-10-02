<?php

namespace App\Db;

class ClickHouseHandler implements HandlerInterface
{
    private \PDO $connection;

    public function __construct(
        string $host,
        string $user,
        string $password,
        string $database
    ) {
        $this->connection = new \PDO("mysql:host=$host;port=9004;dbname=$database", $user, $password);
    }

    public function getStats(): \Generator
    {

        $query = $this->connection->query(<<<SQL

            SELECT 
                fromUnixTimestamp(toUnixTimestamp(date) - toUnixTimestamp(date) % 60) as minute, 
                count(id) AS request_count, 
                avg(length) AS average_length,
                min(date) AS min_date, 
                max(date) AS max_date
            FROM urls 
            GROUP BY minute 
            ORDER BY minute;
            
        SQL, \PDO::FETCH_OBJ);

        while ($row = $query->fetch()) {
            yield $row;
        }

    }
}